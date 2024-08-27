<?php

namespace Database\Seeders;

use App\Models\Conversion;
use Illuminate\Database\Seeder;
use Random\RandomException;

class ConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        Conversion::factory(10)->create();
    }
}
