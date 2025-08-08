<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SuricataAlert;
use App\Services\SuricataAnalysisService;
use App\Services\SecurityReportService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;


class SuricataController extends Controller
{
    private SuricataAnalysisService $analysisService;
    private SecurityReportService $reportService;
    
    public function __construct(SuricataAnalysisService $analysisService, SecurityReportService $reportService)
    {
        $this->analysisService = $analysisService;
        $this->reportService = $reportService;
    }
    
    /**
     * Dashboard monitoring utama dengan analisis komprehensif
     */
   public function index()
    {
        // Get paginated alerts untuk tabel
        $alerts = SuricataAlert::orderByDesc('created_at')->paginate(10);

        // Transform untuk display
        $monitoringData = $alerts->getCollection()->map(function ($alert) {
            return [
                'src_ip'    => $alert->src_ip,
                'dest_ip'   => $alert->dest_ip,
                'protocol'  => $alert->protocol,
                'severity'  => $alert->priority ?? 1,
                'message'   => $alert->message,
                'timestamp' => $alert->created_at ? Carbon::parse($alert->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') . ' WIB' : '-',
            ];
        })->toArray();

        // Get comprehensive analysis - handle empty data gracefully
        try {
            $securityAnalysis = $this->analysisService->analyzeSecurityData();
        } catch (\Exception $e) {
            // Fallback to empty analysis if no data
            $securityAnalysis = [
                'summary' => [
                    'total_alerts' => 0,
                    'high_priority' => 0,
                    'medium_priority' => 0,
                    'low_priority' => 0,
                    'today_alerts' => 0,
                    'yesterday_alerts' => 0,
                    'trend' => 'stable',
                    'trend_percentage' => 0,
                    'average_per_day' => 0
                ],
                'threat_analysis' => [
                    'threat_categories' => [],
                    'top_attackers' => [],
                    'top_targets' => [],
                    'protocol_distribution' => []
                ],
                'network_analysis' => [
                    'internal_threats' => 0,
                    'external_threats' => 0,
                    'hourly_pattern' => [],
                    'daily_pattern' => [],
                    'peak_hours' => [],
                    'network_segments' => []
                ],
                'risk_assessment' => [
                    'risk_score' => 0,
                    'risk_level' => 'Low',
                    'risk_color' => 'success',
                    'factors' => [
                        'high_severity_alerts' => 0,
                        'total_recent_alerts' => 0,
                        'unique_attackers' => 0,
                        'unique_targets' => 0
                    ]
                ],
                'recommendations' => []
            ];
        }
        
        // Generate charts data untuk dashboard
        $chartsData = $this->prepareChartsData($securityAnalysis);

        return view('dashboard_it_manager.dashboard_monitoring.view-monitoring-suricata', [
            'monitoringData' => $monitoringData,
            'logs' => $alerts,
            'securityAnalysis' => $securityAnalysis,
            'chartsData' => $chartsData,
            'lastUpdate' => Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') . ' WIB'
        ]);
    }
    
    /**
     * Dashboard analisis mendalam
     */
    public function securityAnalysis(Request $request)
    {
        $filters = [];
        
        if ($request->has('date_from')) {
            $filters['date_from'] = Carbon::parse($request->date_from)->startOfDay();
        }
        
        if ($request->has('date_to')) {
            $filters['date_to'] = Carbon::parse($request->date_to)->endOfDay();
        }
        
        if ($request->has('severity')) {
            $filters['severity'] = $request->severity;
        }
        
        $analysis = $this->analysisService->analyzeSecurityData($filters);
        $chartsData = $this->prepareChartsData($analysis);
        
        return view('dashboard_it_manager.dashboard_monitoring.security-analysis', [
            'analysis' => $analysis,
            'chartsData' => $chartsData,
            'filters' => $filters
        ]);
    }
    
    /**
     * Generate laporan keamanan
     */
    public function generateReport(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        
        switch ($type) {
            case 'weekly':
                $report = $this->reportService->generateWeeklyReport($date->startOfWeek());
                break;
            case 'monthly':
                $report = $this->reportService->generateMonthlyReport($date->startOfMonth());
                break;
            default:
                $report = $this->reportService->generateDailyReport($date);
        }
        
        return view('dashboard_it_manager.dashboard_monitoring.security-report', [
            'report' => $report
        ]);
    }
    
    /**
     * Export laporan ke PDF
     */
    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        
        switch ($type) {
            case 'weekly':
                $report = $this->reportService->generateWeeklyReport($date->startOfWeek());
                break;
            case 'monthly':
                $report = $this->reportService->generateMonthlyReport($date->startOfMonth());
                break;
            default:
                $report = $this->reportService->generateDailyReport($date);
        }
        
        // Generate PDF (implementation tergantung library yang digunakan)
        return response()->json([
            'message' => 'Report generated successfully',
            'report' => $report
        ]);
    }
    
    /**
     * API endpoint untuk real-time data
     */
    public function getRealtimeData()
    {
        $recentAlerts = SuricataAlert::where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->orderBy('created_at', 'desc')
            ->get();
            
        $summary = [
            'total_alerts_last_hour' => SuricataAlert::where('created_at', '>=', Carbon::now()->subHour())->count(),
            'high_priority_last_hour' => SuricataAlert::where('created_at', '>=', Carbon::now()->subHour())->where('priority', 1)->count(),
            'unique_attackers_last_hour' => SuricataAlert::where('created_at', '>=', Carbon::now()->subHour())->distinct('src_ip')->count(),
            'recent_alerts' => $recentAlerts->take(10)->map(function ($alert) {
                return [
                    'src_ip' => $alert->src_ip,
                    'dest_ip' => $alert->dest_ip,
                    'message' => $alert->message,
                    'priority' => $alert->priority,
                    'time' => $alert->created_at->diffForHumans()
                ];
            })
        ];
        
        return response()->json($summary);
    }
    
    /**
     * Prepare data untuk charts
     */
    private function prepareChartsData(array $analysis): array
    {
        return [
            'severityChart' => [
                'labels' => ['Critical', 'High', 'Medium', 'Low'],
                'datasets' => [[
                    'label' => 'Security Alerts',
                    'data' => [
                        $analysis['summary']['high_priority'] ?? 0,
                        $analysis['summary']['medium_priority'] ?? 0, 
                        $analysis['summary']['low_priority'] ?? 0,
                        0 // placeholder for info level
                    ],
                    'backgroundColor' => ['#dc3545', '#fd7e14', '#ffc107', '#28a745']
                ]]
            ],
            
            'threatCategoriesChart' => [
                'labels' => array_keys($analysis['threat_analysis']['threat_categories'] ?? []),
                'datasets' => [[
                    'label' => 'Threat Categories',
                    'data' => array_values($analysis['threat_analysis']['threat_categories'] ?? []),
                    'backgroundColor' => ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#dc3545']
                ]]
            ],
            
            'timelineChart' => [
                'labels' => array_keys($analysis['network_analysis']['daily_pattern'] ?? []),
                'datasets' => [[
                    'label' => 'Daily Alerts',
                    'data' => array_values($analysis['network_analysis']['daily_pattern'] ?? []),
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'fill' => true
                ]]
            ],
            
            'hourlyPatternChart' => [
                'labels' => array_map(function($hour) { return $hour . ':00'; }, array_keys($analysis['network_analysis']['hourly_pattern'] ?? [])),
                'datasets' => [[
                    'label' => 'Hourly Alerts',
                    'data' => array_values($analysis['network_analysis']['hourly_pattern'] ?? []),
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'fill' => true
                ]]
            ],
            
            'protocolChart' => [
                'labels' => array_column($analysis['threat_analysis']['protocol_distribution'] ?? [], 'protocol'),
                'datasets' => [[
                    'label' => 'Protocol Distribution',
                    'data' => array_column($analysis['threat_analysis']['protocol_distribution'] ?? [], 'count'),
                    'backgroundColor' => ['#17a2b8', '#fd7e14', '#20c997', '#6f42c1', '#e83e8c']
                ]]
            ]
        ];
    }

    private function isAlert($alert): bool
    {
        return stripos($alert->category, 'alert') !== false
            || stripos($alert->message, 'alert') !== false;
    }

    private function sendTelegramNotification($alert): void
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            Log::warning('Telegram credentials not set.');
            return;
        }

        $message = "🚨 *Suricata Alert!*\n"
            . "*Message:* {$alert->message}\n"
            . "*Source:* {$alert->src_ip} → {$alert->dest_ip}\n"
            . "*Protocol:* {$alert->protocol}\n"
            . "*Severity:* {$alert->priority}\n"
            . "*Time:* " . ($alert->created_at ? $alert->created_at->format('Y-m-d H:i:s') : '-');

        try {
            $client = new Client();
            $client->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'form_params' => [
                    'chat_id'    => $chatId,
                    'text'       => $message,
                    'parse_mode' => 'Markdown',
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
        }
    }

    public function monitoringsuricata(): View
    {
        return $this->index();
    }

    public function settingsForm()
    {
        // Coba beberapa cara untuk mendapatkan nilai
        $telegramToken = config('telegram.bot_token') 
            ?: env('TELEGRAM_BOT_TOKEN') 
            ?: '';
            
        $telegramChatId = config('telegram.chat_id') 
            ?: env('TELEGRAM_CHAT_ID') 
            ?: '';
            
        $suricataPath = config('telegram.suricata_log_path') 
            ?: env('SURICATA_LOG_PATH') 
            ?: 'Z:\\eve.json';
        
        // Debug untuk melihat nilai yang diambil
        Log::info('Telegram Settings Debug:', [
            'telegramToken' => $telegramToken,
            'telegramChatId' => $telegramChatId,
            'suricataPath' => $suricataPath,
            'env_token' => env('TELEGRAM_BOT_TOKEN'),
            'env_chat_id' => env('TELEGRAM_CHAT_ID'),
        ]);
        
        return view('dashboard_it_manager.dashboard_monitoring.suricata-settings', [
            'telegramToken' => $telegramToken,
            'telegramChatId' => $telegramChatId,
            'suricataPath' => $suricataPath,
        ]);
    }

    public function saveSettings()
    {
        $data = request()->validate([
            'telegramToken' => 'required|string',
            'telegramChatId' => 'required|string',
            'suricataPath' => 'required|string',
        ]);
        // Simpan ke .env (atau ke database/settings table jika ingin lebih aman)
        $this->updateEnv([
            'TELEGRAM_BOT_TOKEN' => $data['telegramToken'],
            'TELEGRAM_CHAT_ID' => $data['telegramChatId'],
            'SURICATA_LOG_PATH' => $data['suricataPath'],
        ]);
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    private function updateEnv(array $values)
    {
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);
        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$value}";
            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $replacement, $env);
            } else {
                $env .= "\n{$replacement}";
            }
        }
        file_put_contents($envPath, $env);
    }
    public function storeAlert(Request $request): JsonResponse
{
    $data = $request->validate([
        'src_ip'    => 'required|ip',
        'dest_ip'   => 'required|ip',
        'protocol'  => 'required|string',
        'message'   => 'required|string',
        'severity'  => 'nullable|integer',
    ]);

    $alert = SuricataAlert::create([
        'src_ip'   => $data['src_ip'],
        'dest_ip'  => $data['dest_ip'],
        'protocol' => $data['protocol'],
        'message'  => $data['message'],
        'priority' => $data['severity'] ?? 1,
    ]);

    // Kirim Telegram saat alert masuk
    if ($this->isAlert($alert)) {
        $this->sendTelegramNotification($alert);
    }

    return response()->json(['status' => 'success', 'message' => 'Alert received.']);
}
}
