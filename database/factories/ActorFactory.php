<?php

namespace Database\Factories;

use App\Models\Actor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActorFactory extends Factory
{
    protected $model = Actor::class;

    public function definition(): array
    {
        $first = $this->faker->firstName();
        $last = $this->faker->lastName();

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'description' => $this->faker->unique()->paragraph(2),
            'first_name' => $first,
            'last_name' => $last,
            'address' => $this->faker->streetAddress() . ' '
                . $this->faker->city() . ', '
                . $this->faker->stateAbbr(),
            'height' => $this->faker->numberBetween(150, 200) . ' cm',
            'weight' => $this->faker->numberBetween(50, 120) . ' kg',
            'gender' => $this->faker->randomElement(['M', 'F', 'X']),
            'age' => $this->faker->numberBetween(18, 80),
        ];
    }

    public function missingRequired(): self
    {
        return $this->state(fn() => [
            'first_name' => null,
            'last_name' => null,
            'address' => null,
        ]);
    }

}
