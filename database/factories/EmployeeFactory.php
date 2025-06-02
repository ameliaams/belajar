<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['men', 'women']);
        $avatarId = $this->faker->numberBetween(1, 99);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'avatar' => "https://randomuser.me/api/portraits/{$gender}/{$avatarId}.jpg",
            'position' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['IT', 'HR', 'Finance', 'Marketing']),
            'join_date' => $this->faker->date('Y-m-d'),
            'status' => $this->faker->randomElement(['Aktif', 'Non-Aktif']),
            'base_salary' => $this->faker->randomFloat(2, 5000000, 15000000),
            'allowance' => $this->faker->randomFloat(2, 500000, 3000000),
        ];
    }
}
