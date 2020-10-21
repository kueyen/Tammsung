<?php

use Illuminate\Database\Seeder;
use App\Table;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Table::insert([
            //1
            [
                'name' => 'โต๊ะ1',
                'key' =>  (string) Str::uuid(),

                'restaurant_id' => 1
            ],
            //2
            [
                'name' => 'โต๊ะ2',
                'key' =>  (string) Str::uuid(),

                'restaurant_id' => 1
            ],
            //3 Immhee
            [
                'name' => 'โต๊ะ1',
                'key' =>  (string) Str::uuid(),

                'restaurant_id' => 2
            ],
            //4 kkn
            [
                'name' => 'โต๊ะ1',
                'key' =>  (string) Str::uuid(),

                'restaurant_id' => 3
            ],
        ]);
    }
}