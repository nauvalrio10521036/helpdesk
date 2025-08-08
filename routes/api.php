<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/suricata/alerts', function (Request $request) {
    $data = $request->validate([
        'message'    => 'required|string',
        'src_ip'     => 'required|ip',
        'dest_ip'    => 'required|ip',
        'protocol'   => 'required|string',
        'priority'   => 'nullable|integer',
        'created_at' => 'nullable|date',
        "device_id" => 'nullable|exists:networkdevices,device_id',
    ]);

    $alert = new \App\Models\SuricataAlert($data);

    $alert->save();

    return response()->json(['success' => true, 'alert' => $alert], 201);
});
Route::get('/suricata/alerts', function () {
    $alerts = \App\Models\SuricataAlert::orderByDesc('created_at')->limit(100)->get();
    $data = $alerts->map(function ($alert) {
        return [
            'src_ip'    => $alert->src_ip,
            'dest_ip'   => $alert->dest_ip,
            'protocol'  => $alert->protocol,
            'severity'  => $alert->priority ?? 1,
            'message'   => $alert->message,
            'timestamp' => $alert->created_at ? $alert->created_at->format('Y-m-d H:i:s') : '-',
        ];
    });
    return response()->json($data);
});