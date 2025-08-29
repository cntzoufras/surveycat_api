<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {

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
    public function definition() {
        $createdAt = $this->faker->dateTimeBetween('-30 days', 'now');

        return [
            'id'                => $this->faker->uuid(),
            'username'          => $this->faker->userName,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->lastName,
            'role'              => $this->faker->randomElement(['admin', 'premium', 'registered', 'guest']),
            'avatar'            => $this->faker->image,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            // Let the model's 'hashed' cast hash this value using current hasher config
            'password'          => 'password',
            'remember_token'    => Str::random(10),
            'created_at'        => $createdAt,
            'updated_at'        => $createdAt,
        ];
    }
}
