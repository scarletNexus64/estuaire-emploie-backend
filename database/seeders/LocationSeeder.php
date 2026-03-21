<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Douala',
            'Yaoundé',
            'Garoua',
            'Bafoussam',
            'Bamenda',
            'Ngaoundéré',
            'Maroua',
            'Nkongsamba',
            'Limbé',
            'Edéa',
            'Kribi',
            'Bertoua',
            'Buéa',
            'Kumba',
        ];

        foreach ($locations as $location) {
            Location::create([
                'name' => $location,
                'slug' => Str::slug($location),
                'country' => 'Cameroun',
            ]);
        }
    }
}
