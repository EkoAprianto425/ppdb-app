<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = $this->faker->randomElement(['alumni', 'umum', 'anak_pegawai']);
        $educationalLevelId = \App\Models\EducationalLevel::inRandomOrder()->first()->id ?? null;
        
        if ($category === 'anak_pegawai') {
            return [
                'category' => $category,
                'name' => $this->faker->jobTitle(),
                'educational_level_id' => $educationalLevelId,
                'registration_wave_id' => \App\Models\RegistrationWave::inRandomOrder()->first()->id ?? null,
                'amount' => $this->faker->numberBetween(50, 500) * 1000,
                'spp_amount' => $this->faker->numberBetween(50, 200) * 1000,
                'qty' => null,
                'description' => $this->faker->sentence(),
                'apply_to' => null,
                'require_document' => false,
                'is_active' => $this->faker->boolean(80),
            ];
        }

        return [
            'category' => $category,
            'name' => $category === 'alumni' ? 'Alumni ' . $this->faker->year() : 'Prestasi ' . $this->faker->word(),
            'educational_level_id' => $educationalLevelId,
            'registration_wave_id' => null,
            'amount' => $this->faker->numberBetween(100, 1000) * 1000,
            'spp_amount' => null,
            'qty' => $this->faker->numberBetween(5, 50),
            'description' => $this->faker->sentence(),
            'apply_to' => $category,
            'require_document' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
