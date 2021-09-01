<?php

use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i=0; $i < 30; $i++)
        {
            ProductModel::create([
                'title' => $faker->sentence(4),
                'slug' => $faker->slug,
                'subtitle' =>$faker->sentence(5),
                'description' => $faker->text,
                // Multiplié par 100 pour stocker les prix en centimes (bonne pratique)
                'price' => $faker->numberBetween(15, 300) * 100,
                'image' => 'https://via.placeholder.com/200x250'
            ]);
        }
    }
}
