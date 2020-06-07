<?php

use Illuminate\Database\Seeder;
use \App\Category;
use \App\Option;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ["stock photo", "stock video", "sound effects", "music", "logos", "icons", "illustrations"];
        $colors = ['#FFB056', '#e5c61f', '#84da2e', '#27d8a9', '#25c0fc', '#ad55f6', '#f65252'];

        $i = 0;
        foreach($categories as $category){
            $exists = Category::where('name', $category)->first();
            if(!$exists){
                $newCategory = new Category();
                $newCategory->name = $category;
                $newCategory->url = Str::slug($category, '-');
                $newCategory->save();
                $option = new Option();
                $option->name = 'background_color';
                $option->value = $colors[$i];
                $newCategory->option()->save($option);
            }
            $i++;
        }
    }
}
