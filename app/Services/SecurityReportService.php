<?php

namespace App\Services;

use App\Models\SuricataAlert;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class SecurityReportService
{
    private SuricataAnalysisService $analysisService;
    
    public function __construct(SuricataAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }
    
    /**
     * Generate laporan keamanan harian
     */
    public function generateDailyReport(Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();
        
        $filters = [
            'date_from' => $date->startOfDay(),
            'date_to' => $date->endOfDay()
        ];
        
        $analysis = $this->analysisService->analyzeSecurityData($filters);
        
        return [
            'report_type' => 'daily',
            'date' => $date->format('Y-m-d'),
            'generated_at' => Carbon::now(),
            'executive_summary' => $this->generateExecutiveSummary($analysis),
            'detailed_analysis' => $analysis,
            'charts_data' => $this->prepareChartsData($analysis),
            'action_items' => $this->generateActionItems($analysis)
        ];
    }
    
    /**
     * Generate laporan keamanan mingguan
     */
    public function generateWeeklyReport(Carbon $startDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        
        $filters = [
            'date_from' => $startDate,
            'date_to' => $endDate
        ];
        
        $analysis = $this->analysisService->analyzeSecurityData($filters);
        
        // Weekly trend analysis
        $weeklyTrends = $this->analyzeWeeklyTrends($startDate, $endDate);
        
        return [
            'report_type' => 'weekly',
            'period' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            'generated_at' => Carbon::now(),
            'executive_summary' => $this->generateExecutiveSummary($analysis),
            'weekly_trends' => $weeklyTrends,
            'detailed_analysis' => $analysis,
            'charts_data' => $this->prepareChartsData($analysis),
            'compliance_status' => $this->assessComplianceStatus($analysis),
            'action_items' => $this->generateActionItems($analysis)
        ];
    }
    
    /**
     * Generate laporan keamanan bulanan
     */
    public function generateMonthlyReport(Carbon $month = null): array
    {
        $month = $month ?? Carbon::now()->startOfMonth();
        $endDate = $month->copy()->endOfMonth();
        
        $filters = [
            'date_from' => $month,
            'date_to' => $endDate
        ];
        
        $analysis = $this->analysisService->analyzeSecurityData($filters);
        
        // Monthly comparisons
        $previousMonth = $month->copy()->subMonth();
        $previousAnalysis = $this->analysisService->analyzeSecurityData([
            'date_from' => $previousMonth,
            'date_to' => $previousMonth->endOfMonth()
        ]);
        
        $monthlyComparison = $this->compareMonthlyData($analysis, $previousAnalysis);
        
        return [
            'report_type' => 'monthly',
            'period' => $month->format('F Y'),
            'generated_at' => Carbon::now(),
            'executive_summary' => $this->generateExecutiveSummary($analysis),
            'monthly_comparison' => $monthlyComparison,
            'detailed_analysis' => $analysis,
            'charts_data' => $this->prepareChartsData($analysis),
            'compliance_status' => $this->assessComplianceStatus($analysis),
            'security_metrics' => $this->calculateSecurityMetrics($analysis),
            'action_items' => $this->generateActionItems($analysis),
            'recommendations' => $this->generateStrategicRecommendations($analysis)
        ];
    }
    
    /**
     * Generate executive summary
     */
    private function generateExecutiveSummary(array $analysis): array
    {
        $summary = $analysis['summary'];
        $riskAssessment = $analysis['risk_assessment'];
        
        $status = 'normal';
        $statusColor = 'success';
        $statusMessage = 'Sistem keamanan berjalan normal';
        
        if ($riskAssessment['risk_level'] === 'Critical') {
            $status = 'critical';
            $statusColor = 'danger';
            $statusMessage = 'Terdapat ancaman keamanan yang memerlukan tindakan segera';
        } elseif ($riskAssessment['risk_level'] === 'High') {
            $status = 'warning';
            $statusColor = 'warning';
            $statusMessage = 'Tingkat ancaman keamanan tinggi, monitoring diperlukan';
        } elseif ($riskAssessment['risk_level'] === 'Medium') {
            $status = 'caution';
            $statusColor = 'info';
            $statusMessage = 'Tingkat ancaman sedang, perlu perhatian';
        }
        
        $keyPoints = [];
        
        if ($summary['high_priority'] > 0) {
            $keyPoints[] = "Terdeteksi {$summary['high_priority']} alert prioritas tinggi";
        }
        
        if ($summary['trend'] === 'increasing' && $summary['trend_percentage'] > 20) {
            $keyPoints[] = "Peningkatan aktivitas mencurigakan sebesar {$summary['trend_percentage']}%";
        }
        
        if (count($analysis['threat_analysis']['top_attackers']) > 0) {
            $topAttacker = $analysis['threat_analysis']['top_attackers'][0];
            $keyPoints[] = "IP {$topAttacker['ip']} melakukan {$topAttacker['count']} serangan";
        }
        
        return [
            'status' => $status,
            'status_color' => $statusColor,
            'status_message' => $statusMessage,
            'key_points' => $keyPoints,
            'total_alerts' => $summary['total_alerts'],
            'risk_level' => $riskAssessment['risk_level'],
            'risk_score' => $riskAssessment['risk_score']
        ];
    }
    
    /**
     * Prepare data untuk charts/visualization
     */
    private function prepareChartsData(array $analysis): array
    {
        return [
            'severity_distribution' => [
                'labels' => ['High', 'Medium', 'Low'],
                'data' => [
                    $analysis['summary']['high_priority'],
                    $analysis['summary']['medium_priority'],
                    $analysis['summary']['low_priority']
                ],
                'colors' => ['#dc3545', '#ffc107', '#28a745']
            ],
            
            'threat_categories' => [
                'labels' => array_keys($analysis['threat_analysis']['threat_categories']),
                'data' => array_values($analysis['threat_analysis']['threat_categories'])
            ],
            
            'protocol_distribution' => [
                'labels' => array_column($analysis['threat_analysis']['protocol_distribution'], 'protocol'),
                'data' => array_column($analysis['threat_analysis']['protocol_distribution'], 'count')
            ],
            
            'hourly_pattern' => [
                'labels' => array_keys($analysis['network_analysis']['hourly_pattern']->toArray()),
                'data' => array_values($analysis['network_analysis']['hourly_pattern']->toArray())
            ],
            
            'daily_timeline' => [
                'labels' => array_keys($analysis['network_analysis']['daily_pattern']->toArray()),
                'data' => array_values($analysis['network_analysis']['daily_pattern']->toArray())
            ]
        ];
    }
    
    /**
     * Generate action items
     */
    private function generateActionItems(array $analysis): array
    {
        $actionItems = [];
        $riskLevel = $analysis['risk_assessment']['risk_level'];
        
        // Critical actions
        if ($riskLevel === 'Critical') {
            $actionItems[] = [
                'priority' => 'critical',
                'title' => 'Immediate Response Required',
                'description' => 'Activate incident response team and implement emergency procedures',
                'deadline' => Carbon::now()->addHours(1),
                'assigned_to' => 'Security Team Lead'
            ];
        }
        
        // Top attackers
        if (!empty($analysis['threat_analysis']['top_attackers'])) {
            $topAttacker = $analysis['threat_analysis']['top_attackers'][0];
            if ($topAttacker['count'] > 10) {
                $actionItems[] = [
                    'priority' => 'high',
                    'title' => 'Block Persistent Attacker',
                    'description' => "Block IP {$topAttacker['ip']} which has {$topAttacker['count']} attacks",
                    'deadline' => Carbon::now()->addHours(4),
                    'assigned_to' => 'Network Administrator'
                ];
            }
        }
        
        // Vulnerability assessment
        if ($analysis['summary']['high_priority'] > 5) {
            $actionItems[] = [
                'priority' => 'medium',
                'title' => 'Vulnerability Assessment',
                'description' => 'Conduct thorough vulnerability scan on affected systems',
                'deadline' => Carbon::now()->addDays(1),
                'assigned_to' => 'Security Analyst'
            ];
        }
        
        return $actionItems;
    }
    
    /**
     * Analyze weekly trends
     */
    private function analyzeWeeklyTrends(Carbon $startDate, Carbon $endDate): array
    {
        $dailyData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayAlerts = SuricataAlert::whereDate('created_at', $currentDate)->get();
            
            $dailyData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $currentDate->format('l'),
                'total_alerts' => $dayAlerts->count(),
                'high_priority' => $dayAlerts->where('priority', 1)->count(),
                'unique_attackers' => $dayAlerts->pluck('src_ip')->unique()->count()
            ];
            
            $currentDate->addDay();
        }
        
        return [
            'daily_breakdown' => $dailyData,
            'busiest_day' => collect($dailyData)->sortByDesc('total_alerts')->first(),
            'average_daily_alerts' => collect($dailyData)->avg('total_alerts'),
            'week_over_week_change' => $this->calculateWeekOverWeekChange($startDate)
        ];
    }
    
    /**
     * Compare monthly data
     */
    private function compareMonthlyData(array $currentMonth, array $previousMonth): array
    {
        $current = $currentMonth['summary'];
        $previous = $previousMonth['summary'];
        
        return [
            'total_alerts' => [
                'current' => $current['total_alerts'],
                'previous' => $previous['total_alerts'],
                'change' => $current['total_alerts'] - $previous['total_alerts'],
                'percentage_change' => $previous['total_alerts'] > 0 ? 
                    round((($current['total_alerts'] - $previous['total_alerts']) / $previous['total_alerts']) * 100, 2) : 0
            ],
            'high_priority' => [
                'current' => $current['high_priority'],
                'previous' => $previous['high_priority'],
                'change' => $current['high_priority'] - $previous['high_priority']
            ],
            'unique_attackers' => [
                'current' => count($currentMonth['threat_analysis']['top_attackers']),
                'previous' => count($previousMonth['threat_analysis']['top_attackers']),
            ]
        ];
    }
    
    /**
     * Assess compliance status
     */
    private function assessComplianceStatus(array $analysis): array
    {
        $score = 100;
        $issues = [];
        
        // Response time compliance
        if ($analysis['summary']['high_priority'] > 0) {
            $score -= 20;
            $issues[] = 'High priority alerts require immediate attention';
        }
        
        // Security monitoring coverage
        if (empty($analysis['network_analysis']['hourly_pattern'])) {
            $score -= 15;
            $issues[] = 'Insufficient monitoring coverage detected';
        }
        
        // Incident response
        if ($analysis['risk_assessment']['risk_level'] === 'Critical') {
            $score -= 30;
            $issues[] = 'Critical risk level indicates inadequate incident response';
        }
        
        $complianceLevel = 'Excellent';
        if ($score < 90) $complianceLevel = 'Good';
        if ($score < 75) $complianceLevel = 'Fair';
        if ($score < 60) $complianceLevel = 'Poor';
        
        return [
            'score' => max(0, $score),
            'level' => $complianceLevel,
            'issues' => $issues
        ];
    }
    
    /**
     * Calculate security metrics
     */
    private function calculateSecurityMetrics(array $analysis): array
    {
        $summary = $analysis['summary'];
        
        return [
            'detection_rate' => [
                'value' => $summary['total_alerts'],
                'description' => 'Total threats detected'
            ],
            'response_efficiency' => [
                'value' => round(($summary['low_priority'] / max($summary['total_alerts'], 1)) * 100, 2),
                'description' => 'Percentage of low-priority alerts (indicates good filtering)'
            ],
            'network_coverage' => [
                'value' => count($analysis['network_analysis']['network_segments']),
                'description' => 'Network segments monitored'
            ],
            'threat_diversity' => [
                'value' => count(array_filter($analysis['threat_analysis']['threat_categories'])),
                'description' => 'Types of threats detected'
            ]
        ];
    }
    
    /**
     * Generate strategic recommendations
     */
    private function generateStrategicRecommendations(array $analysis): array
    {
        $recommendations = [];
        
        // Infrastructure recommendations
        if ($analysis['summary']['total_alerts'] > 1000) {
            $recommendations[] = [
                'category' => 'Infrastructure',
                'title' => 'Scale Security Infrastructure',
                'description' => 'High volume of alerts suggests need for additional security infrastructure',
                'priority' => 'high',
                'estimated_cost' => 'High',
                'timeframe' => '3-6 months'
            ];
        }
        
        // Process recommendations
        $recommendations[] = [
            'category' => 'Process',
            'title' => 'Implement Automated Response',
            'description' => 'Automate response to common threat patterns to improve response time',
            'priority' => 'medium',
            'estimated_cost' => 'Medium',
            'timeframe' => '1-3 months'
        ];
        
        // Training recommendations
        if ($analysis['risk_assessment']['risk_level'] !== 'Low') {
            $recommendations[] = [
                'category' => 'Training',
                'title' => 'Enhanced Security Training',
                'description' => 'Conduct advanced security training for staff based on current threat landscape',
                'priority' => 'medium',
                'estimated_cost' => 'Low',
                'timeframe' => '1 month'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Calculate week over week change
     */
    private function calculateWeekOverWeekChange(Carbon $currentWeekStart): array
    {
        $previousWeekStart = $currentWeekStart->copy()->subWeek();
        $previousWeekEnd = $previousWeekStart->copy()->endOfWeek();
        $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();
        
        $currentWeekAlerts = SuricataAlert::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])->count();
        $previousWeekAlerts = SuricataAlert::whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])->count();
        
        $change = $currentWeekAlerts - $previousWeekAlerts;
        $percentageChange = $previousWeekAlerts > 0 ? round(($change / $previousWeekAlerts) * 100, 2) : 0;
        
        return [
            'current_week' => $currentWeekAlerts,
            'previous_week' => $previousWeekAlerts,
            'change' => $change,
            'percentage_change' => $percentageChange,
            'trend' => $change > 0 ? 'increasing' : ($change < 0 ? 'decreasing' : 'stable')
        ];
    }
}
