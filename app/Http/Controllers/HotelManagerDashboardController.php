<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\NetworkDevices;
use App\Models\SuricataAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class HotelManagerDashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk dashboard
        $totalReports = Report::count();
        $totalDevices = NetworkDevices::count();
        $totalAlerts = SuricataAlert::count();
        
        // Report status count
        $pendingReports = Report::where('status', 'pending')->count();
        $inProgressReports = Report::where('status', 'in progress')->count();
        $completedReports = Report::where('status', 'completed')->count();
        
        // Device status count
        $activeDevices = NetworkDevices::where('status', 'active')->count();
        $inactiveDevices = NetworkDevices::where('status', 'inactive')->count();
        
        // Charts data for last 30 days
        $reportsChartData = $this->getReportsChartData();
        $devicesChartData = $this->getDevicesChartData();
        $alertsChartData = $this->getAlertsChartData();
        
        return view('dashboard_hotel_manager.dashboard', compact(
            'totalReports', 'totalDevices', 'totalAlerts',
            'pendingReports', 'inProgressReports', 'completedReports',
            'activeDevices', 'inactiveDevices',
            'reportsChartData', 'devicesChartData', 'alertsChartData'
        ));
    }

    public function reports()
    {
        $reports = Report::with('user')->orderBy('time_report', 'desc')->get();
        $reportsChartData = $this->getReportsChartData();
        
        return view('dashboard_hotel_manager.reports', compact('reports', 'reportsChartData'));
    }

    public function devices()
    {
        $devices = NetworkDevices::with('vlan')->orderBy('created_at', 'desc')->get();
        $devicesChartData = $this->getDevicesChartData();
        
        return view('dashboard_hotel_manager.devices', compact('devices', 'devicesChartData'));
    }

    public function alerts()
    {
        $alerts = SuricataAlert::orderBy('created_at', 'desc')->get();
        $alertsChartData = $this->getAlertsChartData();
        
        return view('dashboard_hotel_manager.alerts', compact('alerts', 'alertsChartData'));
    }

    private function getReportsChartData()
    {
        $data = Report::select(
            DB::raw('DATE(time_report) as date'),
            DB::raw('COUNT(*) as count'),
            'status'
        )
        ->where('time_report', '>=', Carbon::now()->subDays(30))
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Pending',
                    'data' => [],
                    'backgroundColor' => '#ffc107',
                    'borderColor' => '#ffc107',
                ],
                [
                    'label' => 'In Progress',
                    'data' => [],
                    'backgroundColor' => '#17a2b8',
                    'borderColor' => '#17a2b8',
                ],
                [
                    'label' => 'Completed',
                    'data' => [],
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                ]
            ]
        ];

        // Process data for chart
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        $chartData['labels'] = $dates->map(function($date) {
            return Carbon::parse($date)->format('M d');
        })->toArray();

        foreach ($dates as $date) {
            $pending = $data->where('date', $date)->where('status', 'pending')->first();
            $inProgress = $data->where('date', $date)->where('status', 'in progress')->first();
            $completed = $data->where('date', $date)->where('status', 'completed')->first();

            $chartData['datasets'][0]['data'][] = $pending ? $pending->count : 0;
            $chartData['datasets'][1]['data'][] = $inProgress ? $inProgress->count : 0;
            $chartData['datasets'][2]['data'][] = $completed ? $completed->count : 0;
        }

        return $chartData;
    }

    private function getDevicesChartData()
    {
        $data = NetworkDevices::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            'status'
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date', 'status')
        ->orderBy('date')
        ->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Active',
                    'data' => [],
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                ],
                [
                    'label' => 'Inactive',
                    'data' => [],
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                ]
            ]
        ];

        // Process data for chart
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        $chartData['labels'] = $dates->map(function($date) {
            return Carbon::parse($date)->format('M d');
        })->toArray();

        foreach ($dates as $date) {
            $active = $data->where('date', $date)->where('status', 'active')->first();
            $inactive = $data->where('date', $date)->where('status', 'inactive')->first();

            $chartData['datasets'][0]['data'][] = $active ? $active->count : 0;
            $chartData['datasets'][1]['data'][] = $inactive ? $inactive->count : 0;
        }

        return $chartData;
    }

    private function getAlertsChartData()
    {
        $data = SuricataAlert::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            'priority'
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date', 'priority')
        ->orderBy('date')
        ->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'High Priority',
                    'data' => [],
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'fill' => false,
                ],
                [
                    'label' => 'Medium Priority',
                    'data' => [],
                    'backgroundColor' => '#ffc107',
                    'borderColor' => '#ffc107',
                    'fill' => false,
                ],
                [
                    'label' => 'Low Priority',
                    'data' => [],
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                    'fill' => false,
                ]
            ]
        ];

        // Process data for chart
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        $chartData['labels'] = $dates->map(function($date) {
            return Carbon::parse($date)->format('M d');
        })->toArray();

        foreach ($dates as $date) {
            $high = $data->where('date', $date)->where('priority', 1)->first();
            $medium = $data->where('date', $date)->where('priority', 2)->first();
            $low = $data->where('date', $date)->where('priority', 3)->first();

            $chartData['datasets'][0]['data'][] = $high ? $high->count : 0;
            $chartData['datasets'][1]['data'][] = $medium ? $medium->count : 0;
            $chartData['datasets'][2]['data'][] = $low ? $low->count : 0;
        }

        return $chartData;
    }

    public function downloadReportsExcel()
    {
        $reports = Report::with('user')->get();
        
        $filename = 'laporan_reports_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, [
                'ID Laporan',
                'Judul',
                'Deskripsi',
                'Lokasi',
                'Pelapor',
                'Status',
                'Prioritas',
                'Waktu Laporan',
                'Waktu Selesai'
            ]);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->report_id,
                    $report->title,
                    $report->description,
                    $report->lokasi,
                    $report->user ? $report->user->nama : 'N/A',
                    $report->status,
                    $report->prioritas,
                    $report->time_report,
                    $report->time_finished
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadDevicesExcel()
    {
        $devices = NetworkDevices::with('vlan')->get();
        
        $filename = 'laporan_perangkat_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($devices) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, [
                'ID Perangkat',
                'Nama',
                'Tipe',
                'IP Address',
                'MAC Address',
                'VLAN',
                'Lokasi',
                'Keterangan',
                'Status',
                'Uptime',
                'Tanggal Dibuat'
            ]);

            foreach ($devices as $device) {
                fputcsv($file, [
                    $device->device_id,
                    $device->name,
                    $device->tipe,
                    $device->ip_address,
                    $device->mac_address,
                    $device->vlan ? $device->vlan->name : 'N/A',
                    $device->lokasi,
                    $device->keterangan,
                    $device->status,
                    $device->uptime,
                    $device->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadAlertsExcel()
    {
        $alerts = SuricataAlert::get();
        
        $filename = 'laporan_alerts_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($alerts) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, [
                'ID Alert',
                'Source IP',
                'Destination IP',
                'Protocol',
                'Message',
                'Priority',
                'Tanggal Dibuat'
            ]);

            foreach ($alerts as $alert) {
                $priority = '';
                switch($alert->priority) {
                    case 1: $priority = 'High'; break;
                    case 2: $priority = 'Medium'; break;
                    case 3: $priority = 'Low'; break;
                    default: $priority = 'Unknown';
                }
                
                fputcsv($file, [
                    $alert->id,
                    $alert->src_ip,
                    $alert->dest_ip,
                    $alert->protocol,
                    $alert->message,
                    $priority,
                    $alert->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadComprehensiveReport()
    {
        $filename = 'laporan_komprehensif_hotel_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Summary Section
            fputcsv($file, ['LAPORAN KOMPREHENSIF SISTEM HOTEL']);
            fputcsv($file, ['Tanggal Generate: ' . Carbon::now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);
            
            // Reports Summary
            $totalReports = Report::count();
            $pendingReports = Report::where('status', 'pending')->count();
            $inProgressReports = Report::where('status', 'in progress')->count();
            $completedReports = Report::where('status', 'completed')->count();
            
            fputcsv($file, ['=== RINGKASAN LAPORAN ===']);
            fputcsv($file, ['Total Laporan', $totalReports]);
            fputcsv($file, ['Pending', $pendingReports]);
            fputcsv($file, ['In Progress', $inProgressReports]);
            fputcsv($file, ['Completed', $completedReports]);
            fputcsv($file, []);
            
            // Devices Summary
            $totalDevices = NetworkDevices::count();
            $activeDevices = NetworkDevices::where('status', 'active')->count();
            $inactiveDevices = NetworkDevices::where('status', 'inactive')->count();
            
            fputcsv($file, ['=== RINGKASAN PERANGKAT ===']);
            fputcsv($file, ['Total Perangkat', $totalDevices]);
            fputcsv($file, ['Aktif', $activeDevices]);
            fputcsv($file, ['Tidak Aktif', $inactiveDevices]);
            fputcsv($file, []);
            
            // Alerts Summary
            $totalAlerts = SuricataAlert::count();
            $highPriorityAlerts = SuricataAlert::where('priority', 1)->count();
            $mediumPriorityAlerts = SuricataAlert::where('priority', 2)->count();
            $lowPriorityAlerts = SuricataAlert::where('priority', 3)->count();
            
            fputcsv($file, ['=== RINGKASAN ALERTS ===']);
            fputcsv($file, ['Total Alerts', $totalAlerts]);
            fputcsv($file, ['High Priority', $highPriorityAlerts]);
            fputcsv($file, ['Medium Priority', $mediumPriorityAlerts]);
            fputcsv($file, ['Low Priority', $lowPriorityAlerts]);
            fputcsv($file, []);
            
            // Recent Reports Detail
            fputcsv($file, ['=== DETAIL LAPORAN TERBARU (10 Terakhir) ===']);
            fputcsv($file, ['ID', 'Judul', 'Status', 'Prioritas', 'Lokasi', 'Tanggal']);
            
            $recentReports = Report::orderBy('time_report', 'desc')->limit(10)->get();
            foreach ($recentReports as $report) {
                fputcsv($file, [
                    $report->report_id,
                    $report->title,
                    $report->status,
                    $report->prioritas,
                    $report->lokasi,
                    $report->time_report
                ]);
            }
            fputcsv($file, []);
            
            // Recent Alerts Detail
            fputcsv($file, ['=== DETAIL ALERTS TERBARU (10 Terakhir) ===']);
            fputcsv($file, ['ID', 'Source IP', 'Dest IP', 'Protocol', 'Priority', 'Tanggal']);
            
            $recentAlerts = SuricataAlert::orderBy('created_at', 'desc')->limit(10)->get();
            foreach ($recentAlerts as $alert) {
                $priority = '';
                switch($alert->priority) {
                    case 1: $priority = 'High'; break;
                    case 2: $priority = 'Medium'; break;
                    case 3: $priority = 'Low'; break;
                    default: $priority = 'Unknown';
                }
                
                fputcsv($file, [
                    $alert->id,
                    $alert->src_ip,
                    $alert->dest_ip,
                    $alert->protocol,
                    $priority,
                    $alert->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
