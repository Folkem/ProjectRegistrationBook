<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Supervisor;
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
        return [
            'registration_number' => $this->faker->unique()->numberBetween(1, 1000),
            'student' => $this->faker->name(),
            'theme' => $this->faker->realTextBetween(10, 80),
            'supervisor_id' => Supervisor::all()->random()->id,
            'group_id' => Group::all()->random()->id,
            'project_type_id' => ProjectType::all()->random()->id,
            'grade' => $this->faker->numberBetween(60, 100),
            'registered_at' => $this->faker->dateTimeThisYear(),
            'defended_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
