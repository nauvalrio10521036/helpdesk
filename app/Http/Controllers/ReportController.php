<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\NetworkDevices;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user');

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('time_report', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('time_report', '<=', $request->tanggal_sampai);
        }

        // Get all reports for statistics calculation
        $allReports = Report::all();

        // Apply pagination with 10 items per page
        $reports = $query->orderBy('time_report', 'desc')->paginate(10);

        return view('dashboard_it_manager.dashboard_laporan.view-report', compact('reports', 'allReports'));
    }

    public function create()
    {
        $users = User::all();
        $devices = NetworkDevices::all();
        return view('dashboard_it_manager.dashboard_laporan.create-report', compact('users', 'devices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'lokasi' => 'required|string|max:255',
            'user_id' => 'required|exists:user,user_id',
            'device_id' => 'nullable|exists:networkdevices,device_id',
            'status' => 'required|in:waiting,process,finished',
            'prioritas' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file',
        ]);
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }
        
        $validated['time_report'] = now();
        
        // Get first available device as default if device_id is empty
        if (empty($validated['device_id'])) {
            $firstDevice = \App\Models\NetworkDevices::first();
            if ($firstDevice) {
                $validated['device_id'] = $firstDevice->device_id;
            } else {
                // If no devices exist, we'll need to handle this differently
                unset($validated['device_id']);
            }
        }
        
        Report::create($validated);
        return redirect()->route('dashboard_it_manager.view-report')->with('success', 'Report berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->route('dashboard_it_manager.view-report')->with('success', 'Report berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:waiting,process,finished',
        ]);

        $wasAutoCompleted = false;
        
        // If status is being changed to 'finished' and no completion time is set, set it automatically
        if ($validated['status'] === 'finished' && is_null($report->time_finished)) {
            // Set completion time to current date at end of day in WIB timezone
            $wibDateTime = \Carbon\Carbon::now('Asia/Jakarta')->endOfDay();
            $report->time_finished = $wibDateTime;
            $wasAutoCompleted = true;
        }
        
        // If status is changed from 'finished' to something else, clear the completion time
        if ($report->status === 'finished' && $validated['status'] !== 'finished') {
            $report->time_finished = null;
        }

        $report->update($validated);
        
        // Customize success message based on what happened
        $message = 'Status laporan berhasil diupdate!';
        if ($wasAutoCompleted) {
            $message = 'Status berhasil diubah menjadi selesai dan tanggal selesai otomatis diisi hari ini!';
        }
        
        return redirect()->route('dashboard_it_manager.view-report')
            ->with('success', $message);
    }
}
