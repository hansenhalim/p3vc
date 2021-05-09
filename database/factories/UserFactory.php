<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User as User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$uE4NcYl3nUXUHyzRSV04Cu4ke8Tsm9XhSe6zMozmi.3bsT/KqCCfe',
            'remember_token' => Str::random(10),
            'menuroles' => 'user'
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'menuroles' => 'user,admin',
            ];
        });
    }
}
