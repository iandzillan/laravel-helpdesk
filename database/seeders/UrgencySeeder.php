<?php

namespace Database\Seeders;

use App\Models\Urgency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UrgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urgency = [
            [
                'name' => 'Ctritical',
                'hours' => 1
            ],
            [
                'name' => 'High',
                'hours' => 2
            ],
            [
                'name' => 'Normal',
                'hours' => 3
            ],
            [
                'name' => 'Lower',
                'hours' => 4
            ],
        ];

        for ($i=0; $i < count($urgency); $i++) { 
            Urgency::create([
                'name'  => $urgency[$i]['name'],
                'hours' => $urgency[$i]['hours'],
            ]);
        }
    }
}
