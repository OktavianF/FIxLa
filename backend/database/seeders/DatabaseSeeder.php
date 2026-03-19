<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Dinas PU',
            'email' => 'admin@fixla.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create regular users
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567891',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567892',
        ]);

        // Create sample reports
        $reports = [
            [
                'user_id' => 2,
                'latitude' => -7.1147,
                'longitude' => 112.4174,
                'address' => 'Jl. Raya Lamongan No. 12',
                'district' => 'Lamongan',
                'damage_level' => 'berat',
                'description' => 'Jalan berlubang besar, sangat berbahaya untuk pengendara motor',
                'priority_score' => 85.5,
                'report_count' => 5,
                'traffic_level' => 0.8,
                'facility_proximity' => 0.7,
                'status' => 'verified',
                'verified_at' => now()->subDays(2),
            ],
            [
                'user_id' => 2,
                'latitude' => -7.1200,
                'longitude' => 112.4220,
                'address' => 'Jl. Veteran Lamongan',
                'district' => 'Lamongan',
                'damage_level' => 'sedang',
                'description' => 'Retakan panjang di sepanjang jalan',
                'priority_score' => 62.0,
                'report_count' => 3,
                'traffic_level' => 0.6,
                'facility_proximity' => 0.5,
                'status' => 'scheduled',
                'verified_at' => now()->subDays(5),
                'scheduled_at' => now()->subDays(1),
            ],
            [
                'user_id' => 3,
                'latitude' => -7.1050,
                'longitude' => 112.4100,
                'address' => 'Jl. Kusuma Bangsa',
                'district' => 'Deket',
                'damage_level' => 'ringan',
                'description' => 'Permukaan jalan mulai retak kecil',
                'priority_score' => 28.0,
                'report_count' => 1,
                'traffic_level' => 0.3,
                'facility_proximity' => 0.2,
                'status' => 'submitted',
            ],
            [
                'user_id' => 3,
                'latitude' => -7.1300,
                'longitude' => 112.4300,
                'address' => 'Jl. Sunan Drajat',
                'district' => 'Paciran',
                'damage_level' => 'berat',
                'description' => 'Jalan amblas, tidak bisa dilalui kendaraan besar',
                'priority_score' => 92.0,
                'report_count' => 8,
                'traffic_level' => 0.9,
                'facility_proximity' => 0.8,
                'status' => 'under_repair',
                'verified_at' => now()->subDays(10),
                'scheduled_at' => now()->subDays(7),
                'repair_started_at' => now()->subDays(2),
            ],
            [
                'user_id' => 2,
                'latitude' => -7.0950,
                'longitude' => 112.4050,
                'address' => 'Jl. Basuki Rahmat',
                'district' => 'Lamongan',
                'damage_level' => 'sedang',
                'description' => 'Lubang kecil di dekat perempatan',
                'priority_score' => 55.0,
                'report_count' => 2,
                'traffic_level' => 0.7,
                'facility_proximity' => 0.9,
                'status' => 'completed',
                'verified_at' => now()->subDays(15),
                'scheduled_at' => now()->subDays(12),
                'repair_started_at' => now()->subDays(8),
                'completed_at' => now()->subDays(3),
            ],
        ];

        foreach ($reports as $reportData) {
            \App\Models\Report::create($reportData);
        }

        // Create status histories
        $statusHistories = [
            ['report_id' => 1, 'changed_by' => 1, 'from_status' => null, 'to_status' => 'submitted'],
            ['report_id' => 1, 'changed_by' => 1, 'from_status' => 'submitted', 'to_status' => 'verified'],
            ['report_id' => 2, 'changed_by' => 1, 'from_status' => null, 'to_status' => 'submitted'],
            ['report_id' => 2, 'changed_by' => 1, 'from_status' => 'submitted', 'to_status' => 'verified'],
            ['report_id' => 2, 'changed_by' => 1, 'from_status' => 'verified', 'to_status' => 'scheduled'],
            ['report_id' => 4, 'changed_by' => 1, 'from_status' => null, 'to_status' => 'submitted'],
            ['report_id' => 4, 'changed_by' => 1, 'from_status' => 'submitted', 'to_status' => 'verified'],
            ['report_id' => 4, 'changed_by' => 1, 'from_status' => 'verified', 'to_status' => 'scheduled'],
            ['report_id' => 4, 'changed_by' => 1, 'from_status' => 'scheduled', 'to_status' => 'under_repair'],
        ];

        foreach ($statusHistories as $sh) {
            \App\Models\StatusHistory::create($sh);
        }

        // Create sample notifications
        \App\Models\AppNotification::create([
            'user_id' => 2,
            'report_id' => 1,
            'title' => 'Laporan telah diverifikasi',
            'message' => 'Laporan #1 di Jl. Raya Lamongan telah diverifikasi oleh petugas.',
            'type' => 'status_update',
            'is_read' => false,
        ]);

        \App\Models\AppNotification::create([
            'user_id' => 3,
            'report_id' => 4,
            'title' => 'Perbaikan dimulai',
            'message' => 'Perbaikan jalan di Jl. Sunan Drajat telah dimulai.',
            'type' => 'status_update',
            'is_read' => true,
        ]);
    }
}