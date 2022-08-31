<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class PdfFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['path' => "string"])]
    public function definition(): array
    {
        return [
            'path' => 'pdfs/' . $this->faker->word . '.pdf',
        ];
    }
}
