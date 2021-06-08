<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialities = [
            'КН', 'ЗВ', 'ТО', 'БС', 'БО', 'ФК', 'ПР',
        ];
        for ($year = 1; $year <= 4; $year++) {
            foreach ($specialities as $speciality) {
                Group::query()->create(['name' => sprintf("%s-%s1", $speciality, $year)]);
            }
        }
    }
}
