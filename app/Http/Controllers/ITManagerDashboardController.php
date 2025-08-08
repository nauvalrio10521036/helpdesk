<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NetworkDevices;
use App\Models\Report;
use App\Models\SuricataAlert;
use App\Models\DeviceLog;
use App\Models\Vlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ITManagerDashboardController extends Controller
{
    public function index()
    {
        // Statistics Cards
        $totalUsers = User::count();
        $totalDevices = NetworkDevices::count();
        $totalReports = Report::count();
        $totalAlerts = SuricataAlert::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $highPriorityAlerts = SuricataAlert::where('priority', 'high')->count();
        $totalVlans = Vlan::count();

        // Device Status Chart Data
        $devicesByStatus = NetworkDevices::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Device Types Chart Data
        $devicesByType = NetworkDevices::select('tipe', DB::raw('count(*) as total'))
            ->groupBy('tipe')
            ->get();

        // Reports by Status Chart Data
        $reportsByStatus = Report::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Reports by Priority Chart Data
        $reportsByPriority = Report::select('prioritas', DB::raw('count(*) as total'))
            ->groupBy('prioritas')
            ->get();

        // Alerts by Priority Chart Data
        $alertsByPriority = SuricataAlert::select('priority', DB::raw('count(*) as total'))
            ->whereNotNull('priority')
            ->groupBy('priority')
            ->limit(10)
            ->get();

        // Recent Activities/Events
        $recentReports = Report::with('user')
            ->orderBy('time_report', 'desc')
            ->limit(5)
            ->get();

        $recentAlerts = SuricataAlert::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly Statistics for Trend Charts
        $monthlyReports = Report::select(
                DB::raw('MONTH(time_report) as month'),
                DB::raw('YEAR(time_report) as year'),
                DB::raw('count(*) as total')
            )
            ->whereYear('time_report', Carbon::now()->year)
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        $monthlyAlerts = SuricataAlert::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        // VLAN Utilization
        $vlanUtilization = Vlan::withCount('devices')->get();

        // Network Health Score (simple calculation)
        $networkHealthScore = $this->calculateNetworkHealthScore();

        return view('dashboard_it_manager.dashboard', compact(
            'totalUsers',
            'totalDevices',
            'totalReports',
            'totalAlerts',
            'pendingReports',
            'highPriorityAlerts',
            'totalVlans',
            'devicesByStatus',
            'devicesByType',
            'reportsByStatus',
            'reportsByPriority',
            'alertsByPriority',
            'recentReports',
            'recentAlerts',
            'monthlyReports',
            'monthlyAlerts',
            'vlanUtilization',
        ));
    }

    private function calculateNetworkHealthScore()
    {
        $totalDevices = NetworkDevices::count();
        $criticalAlerts = SuricataAlert::where('priority', 'high')->count();
        $pendingReports = Report::where('status', 'pending')->count();

        if ($totalDevices === 0) {
            return 0;
        }
        $alertsPenalty = min($criticalAlerts * 5, 30); // Maximum 30 point penalty
        $reportsPenalty = min($pendingReports * 2, 20); // Maximum 20 point penalty

        $healthScore = max(0, $alertsPenalty - $reportsPenalty);

        return round($healthScore, 1);
    }
}
