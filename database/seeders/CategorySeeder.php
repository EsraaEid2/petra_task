<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run()
    {
        $category1 = Category::create(['name' => 'Electronics']);
        $category2 = Category::create(['name' => 'Clothing']);
        $category3 = Category::create(['name' => 'Home & Garden']);

        Subcategory::create(['name' => 'Mobile Phones', 'category_id' => $category1->id]);
        Subcategory::create(['name' => 'Laptops', 'category_id' => $category1->id]);
        Subcategory::create(['name' => 'Men\'s Fashion', 'category_id' => $category2->id]);
        Subcategory::create(['name' => 'Women\'s Fashion', 'category_id' => $category2->id]);
        Subcategory::create(['name' => 'Furniture', 'category_id' => $category3->id]);
        Subcategory::create(['name' => 'Gardening Tools', 'category_id' => $category3->id]);
    }

}