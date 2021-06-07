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
            for ($number = 1; $number <= 3; $number++) {
                foreach ($specialities as $speciality) {
                    Group::query()->create(['name' => "$speciality-$year$number"]);
                }
            }
        }
    }
}
