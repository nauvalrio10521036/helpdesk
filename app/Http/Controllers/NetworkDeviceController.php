<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NetworkDevices;
use App\Models\DeviceLog;
use App\Models\Vlan;

class NetworkDeviceController extends Controller
{
    // Device Management Methods
    public function dperangkat()
    {
        $devices = NetworkDevices::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.view-data-perangkat', compact('devices'));
    }

    public function create()
    {
        $vlans = Vlan::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.create-perangkat', compact('vlans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tipe' => 'required|string',
            'ip_address' => 'required|ip|unique:networkdevices,ip_address',
            'mac_address' => 'required|string|unique:networkdevices,mac_address',
            'vlan_id' => 'required|exists:vlan,vlan_id',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        NetworkDevices::create([
            'name' => $request->name,
            'tipe' => $request->tipe,
            'ip_address' => $request->ip_address,
            'mac_address' => $request->mac_address,
            'vlan_id' => $request->vlan_id,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'uptime' => $request->uptime,
        ]);

        return redirect()->route('dashboard_it_manager.view-data-perangkat')
            ->with('success', 'Perangkat jaringan berhasil ditambahkan!');
    }

    public function edit($device_id)
    {
        $device = NetworkDevices::findOrFail($device_id);
        $vlans = Vlan::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.edit-perangkat', compact('device', 'vlans'));
    }

    public function update(Request $request, $device_id)
    {
        $device = NetworkDevices::findOrFail($device_id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'tipe' => 'required|string',
            'ip_address' => 'required|ip|unique:networkdevices,ip_address,' . $device_id . ',device_id',
            'mac_address' => 'required|string|unique:networkdevices,mac_address,' . $device_id . ',device_id',
            'vlan_id' => 'required|exists:vlan,vlan_id',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $device->update([
            'name' => $request->name,
            'tipe' => $request->tipe,
            'ip_address' => $request->ip_address,
            'mac_address' => $request->mac_address,
            'vlan_id' => $request->vlan_id,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'uptime' => $request->uptime,
        ]);

        return redirect()->route('dashboard_it_manager.view-data-perangkat')
            ->with('success', 'Perangkat berhasil diupdate!');
    }

    public function destroy($device_id)
    {
        $device = NetworkDevices::findOrFail($device_id);
        $device->delete();
        
        return redirect()->route('dashboard_it_manager.view-data-perangkat')
            ->with('success', 'Perangkat berhasil dihapus!');
    }

    // VLAN Management Methods
    public function vlan()
    {
        $vlans = Vlan::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.view-vlan', compact('vlans'));
    }

    public function createVlan()
    {
        return view('dashboard_it_manager.dashboard_data_perangkat.create-vlan');
    }

    public function storeVlan(Request $request)
    {
        $request->validate([
            'name_vlan' => 'required|string|max:255',
            'subnet' => 'required|string|max:255',
            'name_port' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Vlan::create([
            'name_vlan' => $request->name_vlan,
            'subnet' => $request->subnet,
            'name_port' => $request->name_port,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('dashboard_it_manager.view-vlan')
            ->with('success', 'VLAN berhasil ditambahkan!');
    }

    public function editVlan($vlan_id)
    {
        $vlan = Vlan::findOrFail($vlan_id);
        return view('dashboard_it_manager.dashboard_data_perangkat.edit-vlan', compact('vlan'));
    }

    public function updateVlan(Request $request, $vlan_id)
    {
        $vlan = Vlan::findOrFail($vlan_id);
        
        $request->validate([
            'name_vlan' => 'required|string|max:255',
            'subnet' => 'required|string|max:255',
            'name_port' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $vlan->update([
            'name_vlan' => $request->name_vlan,
            'subnet' => $request->subnet,
            'name_port' => $request->name_port,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('dashboard_it_manager.view-vlan')
            ->with('success', 'VLAN berhasil diupdate!');
    }

    public function destroyVlan($vlan_id)
    {
        $vlan = Vlan::findOrFail($vlan_id);
        $vlan->delete();
        
        return redirect()->route('dashboard_it_manager.view-vlan')
            ->with('success', 'VLAN berhasil dihapus!');
    }
}
