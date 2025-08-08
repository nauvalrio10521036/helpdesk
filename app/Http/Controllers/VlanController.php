<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vlan;

class VlanController extends Controller
{
    public function index()
    {
        $vlans = Vlan::all();
        return view('dashboard_it_manager.dashboard_data_perangkat.vlan', compact('vlans'));
    }

    public function create()
    {
        return view('dashboard_it_manager.dashboard_data_perangkat.create-vlan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_vlan' => 'required',
            'subnet' => 'required',
            'name_port' => 'required',
        ]);
        Vlan::create($request->all());
        return redirect()->route('dashboard_it_manager.vlan')->with('success', 'VLAN berhasil ditambah!');
    }

    public function edit($id)
    {
        $vlan = Vlan::findOrFail($id);
        return view('dashboard_it_manager.dashboard_data_perangkat.edit-vlan', compact('vlan'));
    }

    public function update(Request $request, $id)
    {
        $vlan = Vlan::findOrFail($id);
        $request->validate([
            'name_vlan' => 'required',
            'subnet' => 'required',
            'name_port' => 'required',
        ]);
        $vlan->update($request->all());
        return redirect()->route('dashboard_it_manager.vlan')->with('success', 'VLAN berhasil diupdate!');
    }

    public function destroy($id)
    {
        $vlan = Vlan::findOrFail($id);
        $vlan->delete();
        return redirect()->route('dashboard_it_manager.vlan')->with('success', 'VLAN berhasil dihapus!');
    }
}
