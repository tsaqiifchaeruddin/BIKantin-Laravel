<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->bothify('????##'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'siswa',
            'kelas' => fake()->randomElement(['X RPL 1', 'XI RPL 1', 'XII TKJ 2']),
            'no_wa' => '08'.fake()->numerify('##########'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin', 'kelas' => null, 'no_wa' => null]);
    }

    public function kasir(): static
    {
        return $this->state(fn () => ['role' => 'kasir', 'kelas' => null, 'no_wa' => null]);
    }
}
