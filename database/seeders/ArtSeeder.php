<?php

namespace Database\Seeders;

use App\Models\Art;
use Illuminate\Database\Seeder;

class ArtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Art::factory(100)->create();
    }
}
