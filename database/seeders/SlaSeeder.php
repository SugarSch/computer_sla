<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SlaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sla_priorities')->insert([
            [
                'name' => 'Critical',
                'response_time_minutes' => 60,
                'resolve_time_minutes' => 240,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'High',
                'response_time_minutes' => 240,
                'resolve_time_minutes' => 1440,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Normal',
                'response_time_minutes' => 1440,
                'resolve_time_minutes' => 4320,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
