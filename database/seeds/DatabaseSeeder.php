<?php

use App\Restaurant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RestaurantSeeder::class,
            CategorySeeder::class,
            FoodSeeder::class,
            TableSeeder::class,
            UsersSeeder::class

        ]);
    }
}