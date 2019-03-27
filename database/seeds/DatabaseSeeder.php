<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->call('CategoriesTableSeeder');
        $this->call('ProductsTableSeeder');
    }
}

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('users')->delete();

        \App\User::create([
            'name' => 'Administrator',
            'email' => 'admin@myproducts.com',
            'password' => Hash::make('admin123')
        ]);
    }
}

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('categories')->delete();

        $categories = factory(\App\Category::class, 5)->make()->toArray();

        foreach ($categories as $category)
        {
            \App\Category::create($category);
        }
    }
}

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('products')->delete();

        $products = factory(\App\Product::class, 100)->make()->toArray();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        foreach ($products as $product)
        {
            $imageUrl = $product['image'];
            
            curl_setopt($ch, CURLOPT_URL, $imageUrl);
            $image = curl_exec($ch);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            
            $product['image'] = "data:{$contentType};base64," . base64_encode($image);
            
            \App\Product::create($product);
        }

        curl_close($ch);
    }
}
