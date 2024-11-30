<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Automatically create a User if none exists
            'name' => $this->faker->name,
            'profile_created_by' => $this->faker->randomElement(['self', 'parent', 'sibling', 'relative', 'friend']),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'dob' => $this->faker->date('Y-m-d', '2000-01-01'),
            'mother_tongue' => $this->faker->languageCode,
            'subcaste' => $this->faker->word,
            'sub_caste_details' => $this->faker->sentence,
            'willing_to_marry_from_subcaste' => $this->faker->randomElement(['yes', 'no']),
            'marital_status' => $this->faker->randomElement(['Unmarried', 'Widower', 'Divorced', 'Separated']),
            'country_living_in' => $this->faker->country,
            'residing_state' => $this->faker->state,
            'residing_city' => $this->faker->city,
            'citizenship' => $this->faker->country,
            'height' => $this->faker->randomFloat(2, 4.5, 6.5) . ' ft',
            'education' => $this->faker->jobTitle,
            'employed_in' => $this->faker->randomElement(['Government', 'Private', 'Self Employed']),
            'occupation' => $this->faker->jobTitle,
            'annual_income' => $this->faker->numberBetween(10000, 1000000) . ' USD',
            'physical_status' => $this->faker->randomElement(['normal', 'physically_challenged']),
            'family_status' => $this->faker->randomElement(['middle_class']),
            'family_type' => $this->faker->randomElement(['joint_family', 'nuclear_family']),
            'about_me' => $this->faker->paragraph,
            'dosham' => $this->faker->randomElement(['yes', 'no', 'donot_know']),
            'star_nakshatram' => $this->faker->word,
            'rasi' => $this->faker->word,
            'gothram' => $this->faker->word,
            'time_of_birth' => $this->faker->time('H:i'),
            'country_of_birth' => $this->faker->country,
            'state_of_birth' => $this->faker->state,
            'city_of_birth' => $this->faker->city,
            'horoscope_chart_style' => $this->faker->randomElement(['North Indian', 'South Indian', 'Other']),
        ];
    }
}
