<?php

use Illuminate\Database\Seeder;
use App\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::insert([
            // 1
            [
                'name' => 'ตะหลิวทอง',
                'profile_url' => '/images/restaurant/s1.png',
                'description' => 'หลังมหาวิทยาลัยศิลปากรเพชรบุรี',
                'key' => 'taliew'
            ],
            // 2
            [
                'name' => 'อิ่มหมี',
                'profile_url' => '/images/restaurant/s2.png',
                'description' => 'lorem',
                'key' => 'imhee'
            ],
            // 3
            [
                'name' => 'ครัวคุณน้อย',
                'profile_url' => '/images/restaurant/s3.png',
                'description' => 'lorem',
                'key' => 'kunnoi'
            ],
        ]);
    }
}
