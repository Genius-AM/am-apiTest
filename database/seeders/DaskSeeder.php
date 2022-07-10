<?php

namespace Database\Seeders;

use App\Models\Desk;
use Database\factories\DeskFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Desk::factory()->count(10)->create();
    }
}
