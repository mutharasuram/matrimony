<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AstrologyDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $rasiList = [
            'Mesham (Aries)',
            'Rishabam (Taurus)',
            'Midhunam (Gemini)',
            'Karkadam (Cancer)',
            'Simmam (Leo)',
            'Kanni (Virgo)',
            'Thulaam (Libra)',
            'Viruchigam (Scorpio)',
            'Dhanusu (Sagittarius)',
            'Magaram (Capricorn)',
            'Kumbam (Aquarius)',
            'Meenam (Pisces)'
        ];

        $starList = [
            'Ashwini', 'Bharani', 'Karthika', 'Rohini', 'Mrigasira', 'Thiruvathirai',
            'Punarpoosam', 'Poosam', 'Aayilyam', 'Makam', 'Pooram', 'Uthiram',
            'Hastham', 'Chithirai', 'Swathi', 'Visakam', 'Anusham', 'Kettai',
            'Moolam', 'Pooradam', 'Uthiradam', 'Thiruvonam', 'Avittam', 'Sadayam',
            'Poorattathi', 'Uthirattathi'
        ];

        $data = [];

        foreach ($rasiList as $rasi) {
            $data[] = ['type' => 'rasi', 'name' => $rasi, 'created_at' => now(), 'updated_at' => now()];
        }

        foreach ($starList as $star) {
            $data[] = ['type' => 'star', 'name' => $star, 'created_at' => now(), 'updated_at' => now()];
        }

        DB::table('astrology_details')->insert($data);
    }
}
