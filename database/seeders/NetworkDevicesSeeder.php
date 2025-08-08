<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NetworkDevices;
use App\Models\Vlan;

class NetworkDevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, check if VLAN exists, if not create a default one
        $defaultVlan = Vlan::first();
        if (!$defaultVlan) {
            $defaultVlan = Vlan::create([
                'name_vlan' => 'Default VLAN',
                'subnet' => '192.168.1.0/24',
                'name_port' => 'eth0',
                'deskripsi' => 'Default VLAN for system',
            ]);
        }

        // Create a default network device
        NetworkDevices::create([
            'name' => 'Default Device',
            'tipe' => 'switch_hub',
            'ip_address' => '192.168.1.1',
            'mac_address' => '00:00:00:00:00:01',
            'vlan_id' => $defaultVlan->vlan_id,
            'lokasi' => 'Server Room',
            'keterangan' => 'Default device for reports without specific device',
            'status' => 'aktif',
        ]);
    }
}
