<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Todo::class;
    public function definition(): array
    {
        return [
            'title' => $this ->faker -> sentence,
            'body' => $this -> faker -> paragraph,
            'completed' => rand(0, 1),
            'created_by' => rand(1,10)
        ];
    }
}