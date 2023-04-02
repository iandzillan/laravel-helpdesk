<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $technician = User::where('role', 'Technician')->pluck('id');

        $categories = Category::all();

        $sub_category_hardware = [
            'Server',
            'Desktop',
            'Laptop',
            'Printer',
        ];

        $sub_category_software = [
            'EP Lite',
            'License Office',
            'Email',
            'Others Productivity'
        ];

        $sub_category_request = [
            'Data Backup',
            'Data Restore',
            'New Software Request',
            'User / Equipment Move or Change',
            'New User / User Leaving'
        ];

        $sub_category_service = [
            'Email',
            'File Storage',
            'Printing',
            'Internet',
            'Intranet',
            'Networking',
            'Telecomunication'
        ];

        foreach ($categories as $category) {
            switch ($category->name) {
                case 'Hardware':
                    for ($i=0; $i < count($sub_category_hardware); $i++) { 
                        SubCategory::create([
                            'category_id' => $category->id,
                            'technician_id' => $faker->randomElement($technician),
                            'name' => $sub_category_hardware[$i]
                        ]);
                    }
                    break;
                
                case 'Software':
                    for ($i=0; $i < count($sub_category_software); $i++) { 
                        SubCategory::create([
                            'category_id' => $category->id,
                            'technician_id' => $faker->randomElement($technician),
                            'name' => $sub_category_software[$i]
                        ]);
                    }
                    break;
                
                case 'Service':
                    for ($i=0; $i < count($sub_category_service); $i++) { 
                        SubCategory::create([
                            'category_id' => $category->id,
                            'technician_id' => $faker->randomElement($technician),
                            'name' => $sub_category_service[$i]
                        ]);
                    }
                    break;
                
                case 'Request':
                    for ($i=0; $i < count($sub_category_request); $i++) { 
                        SubCategory::create([
                            'category_id' => $category->id,
                            'technician_id' => $faker->randomElement($technician),
                            'name' => $sub_category_request[$i]
                        ]);
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }
}
