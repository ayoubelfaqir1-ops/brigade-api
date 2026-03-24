<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create admin user
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@test.com',
            'password' => 'password',
            'role'     => 'admin',
        ]);

        // create categories
        $moroccan = Category::create([
            'name' => 'Moroccan',
            'description' => 'traditional and delecious moroccan products',
            'color' => '#045362',
            ]);
        $italian  = Category::create([
            'name' => 'Italian',
            'description' => 'italients products',
            'color' => '#048392',
            ]);

        // create plats
        Plat::create([
            'name'        => 'Couscous',
            'price'       => 45.00,
            'category_id' => $moroccan->id,
            'is_available' => true,
    
        ]);

        Plat::create([
            'name'        => 'Tajine',
            'price'       => 55.00,
            'category_id' => $moroccan->id,
            'is_available' => true,
        ]);
    }
}
