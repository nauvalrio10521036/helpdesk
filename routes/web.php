<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NetworkDeviceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuricataController;
use App\Http\Controllers\ITManagerDashboardController;
use App\Http\Controllers\HotelManagerDashboardController;
use App\Http\Controllers\ResepsionisController;
use Illuminate\Http\Request;

// Dashboard utama IT Manager
Route::get('/dashboard_it_manager', [ITManagerDashboardController::class, 'index'])
    ->name('dashboard_it_manager.dashboard')->middleware('auth');

Route::get('/dashboard_it_manager/dashboard_user/view', [UserController::class, 'user'])
->name('dashboard_it_manager.view-user');
// Form tambah user
Route::get('/dashboard_it_manager/dashboard_user/create', function () {
    return view('dashboard_it_manager.dashboard_user.create-user');
})->name('dashboard_it_manager.create-user');
Route::post('/store-user', [UserController::class, 'store'])->name('user.store');

// Tampilkan form edit user
Route::get('/dashboard_it_manager/dashboard_user/edit/{user_id}', [UserController::class, 'edit'])
    ->name('dashboard_it_manager.edit-user');
// Proses update user
Route::put('/update-user/{user_id}', [UserController::class, 'update'])->name('user.update');

// Hapus user
Route::delete('/delete-user/{user_id}', [UserController::class, 'destroy'])->name('user.destroy');

// View data perangkat
Route::get('/dashboard_it_manager/dashboard_data_perangkat/view', [NetworkDeviceController::class, 'dperangkat'])->name('dashboard_it_manager.view-data-perangkat');
Route::get('/dashboard_it_manager/dashboard_data_perangkat/vlan', [NetworkDeviceController::class, 'vlan'])->name('dashboard_it_manager.view-vlan');
// Form tambah perangkat
Route::get('/dashboard_it_manager/dashboard_data_perangkat/create', [NetworkDeviceController::class, 'create'])
    ->name('dashboard_it_manager.create-perangkat');
Route::post('/store-perangkat', [NetworkDeviceController::class, 'store'])->name('dashboard_it_manager.store-perangkat');

// Tampilkan form edit perangkat
Route::get('/dashboard_it_manager/dashboard_data_perangkat/edit/{device_id}', [NetworkDeviceController::class, 'edit'])
    ->name('dashboard_it_manager.edit-perangkat');
// Proses update perangkat
Route::put('/update-perangkat/{device_id}', [NetworkDeviceController::class, 'update'])->name('dashboard_it_manager.update-perangkat');
// Hapus perangkat
Route::delete('/delete-perangkat/{device_id}', [NetworkDeviceController::class, 'destroy'])->name('dashboard_it_manager.destroy-perangkat');    

// Form tambah VLAN
Route::get('/dashboard_it_manager/dashboard_data_perangkat/create-vlan', [NetworkDeviceController::class, 'createVlan'])
    ->name('dashboard_it_manager.create-vlan');
Route::post('/store-vlan', [NetworkDeviceController::class, 'storeVlan'])->name('dashboard_it_manager.store-vlan');
// Tampilkan form edit VLAN
Route::get('/dashboard_it_manager/dashboard_data_perangkat/edit-vlan/{vlan_id}', [NetworkDeviceController::class, 'editVlan'])
    ->name('dashboard_it_manager.edit-vlan');
// Proses update VLAN
Route::put('/update-vlan/{vlan_id}', [NetworkDeviceController::class, 'updateVlan'])->name('dashboard_it_manager.update-vlan');
// Hapus VLAN
Route::delete('/delete-vlan/{vlan_id}', [NetworkDeviceController::class, 'destroyVlan'])->name('dashboard_it_manager.destroy-vlan');     

// View report khusus IT Manager 
Route::get('/dashboard_it_manager/dashboard_laporan/view', [ReportController::class, 'index'])
->name('dashboard_it_manager.view-report');
// Form tambah report
Route::get('/dashboard_it_manager/dashboard_laporan/create', [ReportController::class, 'create'])->name('dashboard_it_manager.create-report');
Route::post('/store-report', [ReportController::class, 'store'])->name('dashboard_it_manager.store-report');

// Update status report
Route::patch('/dashboard_it_manager/dashboard_laporan/update-status/{report_id}', [ReportController::class, 'updateStatus'])->name('dashboard_it_manager.update-status');
// Hapus report
Route::delete('/dashboard_it_manager/dashboard_laporan/delete/{report_id}', [ReportController::class, 'destroy'])->name('dashboard_it_manager.destroy-report');
// Monitoring Keamanan 
Route::get('/dashboard_it_manager/dashboard_monitoring/view-monitoring-suricata', [SuricataController::class, 'index'])
    ->name('dashboard_it_manager.view-monitoring-suricata');

// Security Analysis Routes
Route::get('/dashboard_it_manager/dashboard_monitoring/security-analysis', [SuricataController::class, 'securityAnalysis'])
    ->name('suricata.security-analysis');

// Security Reports Routes
Route::get('/dashboard_it_manager/dashboard_monitoring/security-report', [SuricataController::class, 'generateReport'])
    ->name('suricata.generate-report');
Route::get('/dashboard_it_manager/dashboard_monitoring/export-report', [SuricataController::class, 'exportReport'])
    ->name('suricata.export-report');

// Real-time API Routes
Route::get('/api/suricata/realtime-data', [SuricataController::class, 'getRealtimeData'])
    ->name('suricata.realtime-data');

// Store Alert API (for Suricata integration)
Route::post('/api/suricata/store-alert', [SuricataController::class, 'storeAlert'])
    ->name('suricata.store-alert');

// Main Suricata routes
Route::name('suricata.')->prefix('suricata')->group(function () {
    Route::get('/', [SuricataController::class, 'index'])->name('index');
    Route::get('/analysis', [SuricataController::class, 'securityAnalysis'])->name('analysis');
    Route::get('/reports', [SuricataController::class, 'generateReport'])->name('reports');
    Route::get('/settings', [SuricataController::class, 'settingsForm'])->name('settings');
    Route::post('/settings', [SuricataController::class, 'saveSettings'])->name('save-settings');
});

// Hotel Manager Dashboard Routes
Route::prefix('dashboard_hotel_manager')->name('dashboard_hotel_manager.')->middleware(['hotel_manager', 'auth'])->group(function () {
    Route::get('/', [HotelManagerDashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/reports', [HotelManagerDashboardController::class, 'reports'])
        ->name('reports');
    Route::get('/devices', [HotelManagerDashboardController::class, 'devices'])
        ->name('devices');
    Route::get('/alerts', [HotelManagerDashboardController::class, 'alerts'])
        ->name('alerts');
});

// Resepsionis Dashboard Routes
Route::get('/dashboard_resepsionis', [ResepsionisController::class, 'index'])
    ->name('dashboard_resepsionis.dashboard')->middleware('auth');
Route::get('/dashboard_resepsionis/create-report', [ResepsionisController::class, 'createReport'])
    ->name('dashboard_resepsionis.create-report')->middleware('auth');
Route::post('/dashboard_resepsionis/store-report', [ResepsionisController::class, 'storeReport'])
    ->name('dashboard_resepsionis.store-report')->middleware('auth');
Route::get('/dashboard_resepsionis/riwayat-laporan', [ResepsionisController::class, 'riwayatLaporan'])
    ->name('dashboard_resepsionis.riwayat-laporan')->middleware('auth');
Route::get('/dashboard_resepsionis/detail-laporan/{report_id}', [ResepsionisController::class, 'detailLaporan'])
    ->name('dashboard_resepsionis.detail-laporan')->middleware('auth');
Route::get('/dashboard_resepsionis/download-attachment/{report_id}', [ResepsionisController::class, 'downloadAttachment'])
    ->name('dashboard_resepsionis.download-attachment')->middleware('auth');

// Download routes for Hotel Manager
Route::get('/dashboard_hotel_manager/download/reports', [HotelManagerDashboardController::class, 'downloadReportsExcel'])
    ->name('dashboard_hotel_manager.download.reports');
Route::get('/dashboard_hotel_manager/download/devices', [HotelManagerDashboardController::class, 'downloadDevicesExcel'])
    ->name('dashboard_hotel_manager.download.devices');
Route::get('/dashboard_hotel_manager/download/alerts', [HotelManagerDashboardController::class, 'downloadAlertsExcel'])
    ->name('dashboard_hotel_manager.download.alerts');
Route::get('/dashboard_hotel_manager/download/comprehensive', [HotelManagerDashboardController::class, 'downloadComprehensiveReport'])
    ->name('dashboard_hotel_manager.download.comprehensive');


// Login routes
Route::get('/', function() {
    return redirect(route('login'));
});
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/test', function (Request $request) {
    return response()->json([
        'status' => 'ok',
        'message' => 'API berhasil diakses dari Suricata'
    ]);
});

Route::get('/debug-env', function () {
    return response()->json([
        'TELEGRAM_BOT_TOKEN' => env('TELEGRAM_BOT_TOKEN'),
        'TELEGRAM_CHAT_ID' => env('TELEGRAM_CHAT_ID'),
        'SURICATA_LOG_PATH' => env('SURICATA_LOG_PATH'),
        'config_token' => config('telegram.bot_token'),
        'config_chat_id' => config('telegram.chat_id'),
        'config_path' => config('telegram.suricata_log_path'),
    ]);
})->name('debug.env');