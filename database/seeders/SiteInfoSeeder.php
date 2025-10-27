<?php

namespace Database\Seeders;

use App\Models\SiteInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteInfo::create([
            'name' => 'APLIKASI KOPERASI GIRIARTHA',
            'short_name' => 'KOPERASI GIRIARTHA',
            'description' => '-',
            'keyword' => 'sistem koperasi, koperasi, aplikasi koperasi, aplikasi koperasi giriartha, koperasi giriartha makassar, giriartha makassar',
            'copyright' => '<p>2024 ©&nbsp;<b>Koperasi Giriartha Makassar</b> - All rights reserved</p>',
            'login_bg' => 'login_bg_123456.jpg',
            'login_logo' => 'login_logo_123456.png',
            'headbackend_logo' => 'headbackend_logo_123456.png',
            'headbackend_logo_dark' => 'headbackend_logo_dark_123456.png',
            'headbackend_icon' => 'headbackend_icon_123456.png',
            'headbackend_icon_dark' => 'headbackend_icon_dark_123456.png',
            'user_updated' => NULL,
        ]);
    }
}
