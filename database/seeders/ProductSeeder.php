<?php

namespace Database\Seeders;

use App\Helpers\Base64;
use App\Helpers\RandomImage;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    const PRODUCTS_PER_CATEGORY_QUANTITY = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();

        foreach (Category::all() as $category) {
            $products = Product::factory()->count(self::PRODUCTS_PER_CATEGORY_QUANTITY)->make()->toArray();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            foreach ($products as $product) {
                $imageUrl = RandomImage::url();

                curl_setopt($ch, CURLOPT_URL, $imageUrl);
                $image = curl_exec($ch);
                $imagePath = RandomImage::pathToSave();
                file_put_contents($imagePath, $image);

                $product['image'] = Base64::generateBase64String($imagePath);
                $product['category_id'] = $category->id;

                Product::create($product);
            }

            curl_close($ch);
        }
    }
}
