<?php

namespace Database\Seeders;

use App\Models\ProfilInstansi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilInstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfilInstansi::create([
            'name' => '-',
            'short_description' => '-',
            'logo' => NULL,
            'phone_number' => NULL,
            'email' => NULL,
            'office_address' => NULL,
            'office_address_coordinate' => NULL,
            'user_updated' => NULL,
        ]);
    }
}
