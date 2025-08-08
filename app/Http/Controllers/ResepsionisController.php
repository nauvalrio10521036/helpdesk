<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\NetworkDevices;
use App\Models\SuricataAlert;

class ResepsionisController extends Controller
{
    public function index()
    {
        // Ambil data untuk dashboard resepsionis
        $totalLaporan = Report::count();
        $totalPerangkat = NetworkDevices::count();
        $totalAlert = SuricataAlert::count();
        
        // Ambil statistik berdasarkan status
        $laporanWaiting = Report::where('status', 'waiting')->count();
        $laporanProcess = Report::where('status', 'process')->count();
        $laporanFinished = Report::where('status', 'finished')->count();
        
        // Ambil laporan terbaru berdasarkan time_report
        $laporanTerbaru = Report::orderBy('time_report', 'desc')->take(5)->get();
        $recentReports = $laporanTerbaru; // Alias untuk kompatibilitas dengan view
        
        return view('dashboard_resepsionis.dashboard', compact(
            'totalLaporan',
            'totalPerangkat', 
            'totalAlert',
            'laporanWaiting',
            'laporanProcess', 
            'laporanFinished',
            'laporanTerbaru',
            'recentReports'
        ));
    }

    public function createReport()
    {
        $devices = NetworkDevices::orderBy('name')->get();
        return view('dashboard_resepsionis.create-report', compact('devices'));
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'lokasi' => 'required',
            'prioritas' => 'required|in:low,medium,high',
            'device_id' => 'nullable|exists:networkdevices,device_id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Handle device_id: if not provided, use the first available device as fallback
        $deviceId = $request->device_id;
        if (empty($deviceId)) {
            $firstDevice = NetworkDevices::first();
            if ($firstDevice) {
                $deviceId = $firstDevice->device_id;
            } else {
                // If no devices exist, return with error
                return back()->withInput()->withErrors(['device_id' => 'Tidak ada perangkat tersedia. Silakan hubungi administrator.']);
            }
        }

        Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'lokasi' => $request->lokasi,
            'user_id' => Auth::id(),
            'device_id' => $deviceId,
            'status' => 'waiting',
            'prioritas' => $request->prioritas,
            'attachment' => $attachmentPath,
            'time_report' => now(),
        ]);

        return redirect()->route('dashboard_resepsionis.dashboard')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    public function riwayatLaporan(Request $request)
    {
        $query = Report::where('user_id', Auth::id());
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply priority filter
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }
        
        // Apply date range filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('time_report', '>=', $request->tanggal_dari);
        }
        
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('time_report', '<=', $request->tanggal_sampai);
        }
        
        $reports = $query->orderBy('time_report', 'desc')->paginate(10);
        
        return view('dashboard_resepsionis.riwayat-laporan', compact('reports'));
    }

    public function detailLaporan($report_id)
    {
        $report = Report::findOrFail($report_id);
        return view('dashboard_resepsionis.detail-laporan', compact('report'));
    }

    public function downloadAttachment($report_id)
    {
        $report = Report::findOrFail($report_id);
        
        if (!$report->attachment) {
            return back()->with('error', 'File attachment tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $report->attachment);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($filePath);
    }
}
