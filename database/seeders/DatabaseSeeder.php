<?php

namespace Database\Seeders;

use App\Models\Supervisor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(SupervisorSeeder::class);
        $this->call(ProjectTypeSeeder::class);
        $this->call(ProjectSeeder::class);
    }
}
