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
        $colors = ['#FFB056', '#FFC33B', '#1BEA3A', '#ED25FC', '#FC2579', '#FC252F', '#1AE6B0'];

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
