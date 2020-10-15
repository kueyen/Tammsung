<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            // ****** ร้านตะหลิว *******
            //1
            [
                'name' => 'อาหารจานเดียว',
                'restaurant_id' => 1
            ],
            //2
            [
                'name' => 'อาหารประเภทยำ',
                'restaurant_id' => 1
            ],
            //3
            [
                'name' => 'อาหารประเภทต้ม',
                'restaurant_id' => 1
            ],
            //4
            [
                'name' => "อาหารพิเศษ",
                'restaurant_id' => 1
            ],
            //5
            [
                'name' => 'พิซซ่า',
                'restaurant_id' => 1
            ],
            //6
            [
                'name' => 'สเต็ก',
                'restaurant_id' => 1
            ],
            //7
            [
                'name' => 'โฮมเมดพาสต้า',
                'restaurant_id' => 1
            ],
            // ***** ร้านอิ่มหมี *****
            //8
            [
                'name' => 'ประเภทสเต็ก',
                'restaurant_id' => 2
            ],
            //9
            [
                'name' => 'อาหารทานเล่น',
                'restaurant_id' => 2
            ],
            // ***** ร้านครัวคุณน้อย *****
            //10
            [
                'name' => 'อาหารประเภทต้มยำ',
                'restaurant_id' => 3
            ],
            //11
            [
                'name' => 'ของหวาน',
                'restaurant_id' => 3
            ],
            //12
            [
                'name' => 'เครื่องดื่ม',
                'restaurant_id' => 1
            ]
            // **
        ]);
    }
}