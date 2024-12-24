<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'title' => 'Smartphone',
                'description' => 'Latest model smartphone with advanced features.',
                'price' => 499.99,
                'stock_quantity' => 50,
                'category_id' => 1, // Electronics
                'subcategory_id' => 1, // Subcategory for Electronics (مثلاً: Smartphones)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Winter Jacket',
                'description' => 'Warm and stylish jacket for winter.',
                'price' => 99.99,
                'stock_quantity' => 30,
                'category_id' => 2, // Fashion
                'subcategory_id' => 2, // Subcategory for Fashion (مثلاً: Jackets)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cooking Book',
                'description' => 'Recipes from around the world.',
                'price' => 19.99,
                'stock_quantity' => 100,
                'category_id' => 3, // Books
                'subcategory_id' => 3, // Subcategory for Books (مثلاً: Cookbooks)
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}