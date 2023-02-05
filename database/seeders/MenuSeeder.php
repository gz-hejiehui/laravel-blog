<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuItems = [
            ['name' => '首页', 'slug' => 'home', 'url' => '/'],
            ['name' => '归档', 'slug' => 'archive', 'url' => '/archive'],
            ['name' => '关于', 'slug' => 'about', 'url' => '/about'],
        ];

        foreach ($menuItems as $menuItem) {
            Menu::query()->updateOrCreate(
                [
                    'slug' => $menuItem['slug'],
                ],
                [
                    'name' => $menuItem['name'],
                    'url' => $menuItem['url'],
                ]
            );
        }
    }
}
