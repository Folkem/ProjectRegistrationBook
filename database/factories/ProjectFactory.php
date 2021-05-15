<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $groups = [
            'КН-11',
            'КН-43',
            'БС-21',
            'ПРз-31'
        ];
        return [
            'student' => $this->faker->name,
            'supervisor' => $this->faker->name,
            'theme' => $this->faker->realTextBetween(10, 80),
            'group' => $this->faker->randomElement($groups),
            'project_type_id' => ProjectType::query()->inRandomOrder()->get()->first()->id
        ];
    }
}
