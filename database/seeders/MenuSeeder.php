<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Menu::insert([
            ['name' => 'Espresso', 'description' => 'Kopi hitam pekat', 'price' => 15000, 'image' => null],
            ['name' => 'Latte', 'description' => 'Kopi dengan susu', 'price' => 20000, 'image' => null],
            ['name' => 'Cappuccino', 'description' => 'Kopi dengan foam susu', 'price' => 20000, 'image' => null],
        ]);
    }
}