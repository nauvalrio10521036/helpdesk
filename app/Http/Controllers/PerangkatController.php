<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NetworkDevices;
use App\Models\Vlan;

class PerangkatController extends Controller
{
    public function index()
    {
        $devices = NetworkDevices::with('vlan')->get();
        return view('dashboard_it_manager.dashboard_data_perangkat.perangkat', compact('devices'));
    }

    public function create()
    {
        $vlans = Vlan::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.create-perangkat', compact('vlans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tipe' => 'required',
            'ip_address' => 'required',
            'mac_address' => 'required|unique:networkdevices,mac_address',
            'vlan_id' => 'required',
            'lokasi' => 'required',
            'status' => 'required',
        ]);
        NetworkDevices::create($request->all());
        return redirect()->route('dashboard_it_manager.perangkat')->with('success', 'Perangkat berhasil ditambah!');
    }

    public function edit($id)
    {
        $device = NetworkDevices::findOrFail($id);
        $vlans = Vlan::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.edit-perangkat', compact('device', 'vlans'));
    }

    public function update(Request $request, $id)
    {
        $device = NetworkDevices::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'tipe' => 'required',
            'ip_address' => 'required',
            'mac_address' => 'required|unique:networkdevices,mac_address,' . $id . ',device_id',
            'vlan_id' => 'required',
            'lokasi' => 'required',
            'status' => 'required',
        ]);
        $device->update($request->all());
        return redirect()->route('dashboard_it_manager.perangkat')->with('success', 'Perangkat berhasil diupdate!');
    }

    public function destroy($id)
    {
        $device = NetworkDevices::findOrFail($id);
        $device->delete();
        return redirect()->route('dashboard_it_manager.perangkat')->with('success', 'Perangkat berhasil dihapus!');
    }
}
