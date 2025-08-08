<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\NetworkDevices;
use App\Models\Report;
use App\Models\SuricataAlert;
use App\Models\DeviceLog;
use App\Models\Vlan;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    public function run()
    {
        // Seed VLANs
        $vlans = [
            ['name_vlan' => 'VLAN-100', 'subnet' => '192.168.1.0/24', 'name_port' => 'Ethernet1/0/1', 'deskripsi' => 'Management VLAN'],
            ['name_vlan' => 'VLAN-200', 'subnet' => '192.168.2.0/24', 'name_port' => 'Ethernet1/0/2', 'deskripsi' => 'User VLAN'],
            ['name_vlan' => 'VLAN-300', 'subnet' => '192.168.3.0/24', 'name_port' => 'Ethernet1/0/3', 'deskripsi' => 'Guest VLAN'],
            ['name_vlan' => 'VLAN-400', 'subnet' => '192.168.4.0/24', 'name_port' => 'Ethernet1/0/4', 'deskripsi' => 'Server VLAN'],
        ];

        foreach ($vlans as $vlan) {
            Vlan::create($vlan);
        }

        // Seed Users
        $users = [
            ['name' => 'Admin IT', 'username' => 'admin', 'password' => bcrypt('password'), 'role' => 'admin'],
            ['name' => 'John Doe', 'username' => 'john', 'password' => bcrypt('password'), 'role' => 'user'],
            ['name' => 'Jane Smith', 'username' => 'jane', 'password' => bcrypt('password'), 'role' => 'user'],
            ['name' => 'Bob Wilson', 'username' => 'bob', 'password' => bcrypt('password'), 'role' => 'technician'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Seed Network Devices
        $devices = [
            ['name' => 'Core Switch 01', 'tipe' => 'Switch', 'ip_address' => '192.168.1.1', 'mac_address' => '00:1B:44:11:3A:B7', 'vlan_id' => 1, 'lokasi' => 'Server Room', 'keterangan' => 'Main core switch', 'status' => 'active', 'uptime' => '99.9%'],
            ['name' => 'Access Point Floor 1', 'tipe' => 'Access Point', 'ip_address' => '192.168.2.10', 'mac_address' => '00:1B:44:11:3A:B8', 'vlan_id' => 2, 'lokasi' => 'Floor 1', 'keterangan' => 'WiFi access point', 'status' => 'active', 'uptime' => '98.5%'],
            ['name' => 'Firewall Main', 'tipe' => 'Firewall', 'ip_address' => '192.168.1.254', 'mac_address' => '00:1B:44:11:3A:B9', 'vlan_id' => 1, 'lokasi' => 'Server Room', 'keterangan' => 'Main firewall', 'status' => 'active', 'uptime' => '99.8%'],
            ['name' => 'Router WAN', 'tipe' => 'Router', 'ip_address' => '192.168.1.253', 'mac_address' => '00:1B:44:11:3A:BA', 'vlan_id' => 1, 'lokasi' => 'Server Room', 'keterangan' => 'WAN router', 'status' => 'inactive', 'uptime' => '95.2%'],
            ['name' => 'Switch Floor 2', 'tipe' => 'Switch', 'ip_address' => '192.168.2.1', 'mac_address' => '00:1B:44:11:3A:BB', 'vlan_id' => 2, 'lokasi' => 'Floor 2', 'keterangan' => 'Floor 2 switch', 'status' => 'active', 'uptime' => '97.3%'],
            ['name' => 'Server Web', 'tipe' => 'Server', 'ip_address' => '192.168.4.10', 'mac_address' => '00:1B:44:11:3A:BC', 'vlan_id' => 4, 'lokasi' => 'Server Room', 'keterangan' => 'Web server', 'status' => 'maintenance', 'uptime' => '99.1%'],
        ];

        foreach ($devices as $device) {
            NetworkDevices::create($device);
        }

        // Seed Reports
        $reports = [
            ['title' => 'Network Connectivity Issue', 'description' => 'Users on floor 2 experiencing intermittent network connectivity', 'lokasi' => 'Floor 2', 'user_id' => 2, 'status' => 'pending', 'prioritas' => 'high', 'time_report' => Carbon::now()->subDays(1)],
            ['title' => 'Printer Not Working', 'description' => 'Printer in reception area not responding', 'lokasi' => 'Reception', 'user_id' => 3, 'status' => 'in_progress', 'prioritas' => 'medium', 'time_report' => Carbon::now()->subDays(2)],
            ['title' => 'WiFi Password Reset', 'description' => 'Guest WiFi password needs to be reset', 'lokasi' => 'Conference Room', 'user_id' => 2, 'status' => 'completed', 'prioritas' => 'low', 'time_report' => Carbon::now()->subDays(3), 'time_finished' => Carbon::now()->subDays(2)],
            ['title' => 'Server Performance Issue', 'description' => 'Web server experiencing high CPU usage', 'lokasi' => 'Server Room', 'user_id' => 4, 'status' => 'pending', 'prioritas' => 'high', 'time_report' => Carbon::now()->subHours(6)],
            ['title' => 'Email Server Maintenance', 'description' => 'Scheduled maintenance for email server', 'lokasi' => 'Server Room', 'user_id' => 1, 'status' => 'completed', 'prioritas' => 'medium', 'time_report' => Carbon::now()->subDays(5), 'time_finished' => Carbon::now()->subDays(4)],
        ];

        foreach ($reports as $report) {
            Report::create($report);
        }

        // Seed Security Alerts
        $alerts = [
            ['src_ip' => '192.168.1.100', 'dest_ip' => '8.8.8.8', 'protocol' => 'TCP', 'message' => 'Potential malware communication detected', 'priority' => 'high', 'created_at' => Carbon::now()->subMinutes(30)],
            ['src_ip' => '192.168.2.50', 'dest_ip' => '192.168.1.1', 'protocol' => 'ICMP', 'message' => 'Port scan activity detected', 'priority' => 'medium', 'created_at' => Carbon::now()->subHours(2)],
            ['src_ip' => '10.0.0.1', 'dest_ip' => '192.168.1.254', 'protocol' => 'TCP', 'message' => 'Possible DDoS attack detected', 'priority' => 'high', 'created_at' => Carbon::now()->subHours(4)],
            ['src_ip' => '192.168.3.25', 'dest_ip' => '192.168.1.10', 'protocol' => 'UDP', 'message' => 'Failed login attempts detected', 'priority' => 'medium', 'created_at' => Carbon::now()->subHours(6)],
            ['src_ip' => '172.16.1.1', 'dest_ip' => '192.168.4.10', 'protocol' => 'TCP', 'message' => 'Unusual network traffic pattern', 'priority' => 'low', 'created_at' => Carbon::now()->subHours(8)],
        ];

        foreach ($alerts as $alert) {
            SuricataAlert::create($alert);
        }

        // Seed Device Logs
        $logs = [
            ['device_id' => 1, 'event' => 'Device status changed to active', 'old_value' => 'inactive', 'new_value' => 'active', 'changed_by' => 'admin', 'created_at' => Carbon::now()->subMinutes(15)],
            ['device_id' => 2, 'event' => 'IP address updated', 'old_value' => '192.168.2.9', 'new_value' => '192.168.2.10', 'changed_by' => 'admin', 'created_at' => Carbon::now()->subHours(1)],
            ['device_id' => 4, 'event' => 'Device went offline', 'old_value' => 'active', 'new_value' => 'inactive', 'changed_by' => 'system', 'created_at' => Carbon::now()->subHours(3)],
            ['device_id' => 6, 'event' => 'Maintenance mode enabled', 'old_value' => 'active', 'new_value' => 'maintenance', 'changed_by' => 'admin', 'created_at' => Carbon::now()->subHours(5)],
            ['device_id' => 3, 'event' => 'Configuration updated', 'old_value' => 'old_config', 'new_value' => 'new_config', 'changed_by' => 'admin', 'created_at' => Carbon::now()->subHours(7)],
        ];

        foreach ($logs as $log) {
            DeviceLog::create($log);
        }

        // Add monthly data for trends
        for ($month = 1; $month <= 12; $month++) {
            // Random reports for each month
            for ($i = 0; $i < rand(5, 15); $i++) {
                Report::create([
                    'title' => 'Monthly Report ' . $month . '-' . $i,
                    'description' => 'Auto-generated report for testing',
                    'lokasi' => 'Various',
                    'user_id' => rand(1, 4),
                    'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
                    'prioritas' => ['low', 'medium', 'high'][rand(0, 2)],
                    'time_report' => Carbon::create(2025, $month, rand(1, 28))
                ]);
            }

            // Random alerts for each month
            for ($i = 0; $i < rand(3, 10); $i++) {
                SuricataAlert::create([
                    'src_ip' => '192.168.' . rand(1, 4) . '.' . rand(1, 254),
                    'dest_ip' => '8.8.8.' . rand(1, 254),
                    'protocol' => ['TCP', 'UDP', 'ICMP'][rand(0, 2)],
                    'message' => 'Monthly alert ' . $month . '-' . $i,
                    'priority' => ['low', 'medium', 'high'][rand(0, 2)],
                    'created_at' => Carbon::create(2025, $month, rand(1, 28))
                ]);
            }
        }
    }
}
