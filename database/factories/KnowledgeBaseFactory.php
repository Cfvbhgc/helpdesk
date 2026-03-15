<?php

namespace Database\Factories;

use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KnowledgeBaseFactory extends Factory
{
    protected $model = KnowledgeBase::class;

    public function definition(): array
    {
        $title = fake()->sentence(5);

        return [
            'title' => $title,
            'content' => fake()->paragraphs(5, true),
            'category' => fake()->randomElement(['Getting Started', 'Account', 'Billing', 'Technical', 'FAQ']),
            'slug' => Str::slug($title),
            'published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['published' => false]);
    }
}
