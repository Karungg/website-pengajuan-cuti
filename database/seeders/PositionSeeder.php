<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['title' => 'Direktur Utama'],
            ['title' => 'Direktur'],
            ['title' => 'SDM'],
            ['title' => 'Kepala Bagian'],
            ['title' => 'Pegawai'],
            ['title' => 'Kepala Kas'],
        ];

        Position::withoutEvents(function () use ($positions) {
            foreach ($positions as $position) {
                Position::create($position);
            }
        });
    }
}
