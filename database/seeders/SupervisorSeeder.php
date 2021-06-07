<?php

namespace Database\Seeders;

use App\Models\Supervisor;
use Illuminate\Database\Seeder;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supervisor::factory()
            ->count(15)
            ->create();
    }
}
