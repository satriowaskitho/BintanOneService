<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Operator',
            'email' => 'admin2102@bps.go.id',
            'password' => bcrypt('02bintanglaut'),
        ]);

        \App\Models\ServiceType::insert([
            ['name' => 'Pelayanan Statistik Terpadu', 'code' => 'A'],
            ['name' => 'Konsultasi Data', 'code' => 'B'],
            ['name' => 'Rekomendasi Statistik', 'code' => 'C'],
        ]);
    }
}
