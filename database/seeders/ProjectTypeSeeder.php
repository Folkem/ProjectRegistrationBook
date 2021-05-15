<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectType::query()->create([
            'name' => 'Курсова робота',
        ]);
        ProjectType::query()->create([
            'name' => 'Курсовий проект',
        ]);
        ProjectType::query()->create([
            'name' => 'Навчальна практика',
        ]);
        ProjectType::query()->create([
            'name' => 'Виробничо-технологічна практика',
        ]);
        ProjectType::query()->create([
            'name' => 'Переддипломна практика',
        ]);
        ProjectType::query()->create([
            'name' => 'Дипломний проект',
        ]);
    }
}
