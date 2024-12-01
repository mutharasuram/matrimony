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
            'mother_tongue' => 'Tamil',
            'subcaste' => $this->faker->randomElement(['Iyer', 'Iyengar', 'Chettiar', 'Nadar', 'Gounder', 'Vanniyar', 'Thevar']),
            'sub_caste_details' => $this->faker->sentence,
            'willing_to_marry_from_subcaste' => $this->faker->randomElement(['yes', 'no']),
            'marital_status' => $this->faker->randomElement(['Unmarried', 'Widower', 'Divorced', 'Separated']),
            'country_living_in' => 'India',
            'residing_state' => 'Tamil Nadu',
            'residing_city' => $this->faker->randomElement([
                'Chennai', 'Coimbatore', 'Madurai', 'Trichy', 'Salem', 'Tirunelveli', 'Erode', 'Tirupur'
            ]),
            'citizenship' => 'India',
            'height' => $this->faker->randomFloat(2, 4.5, 6.5) . ' ft',
            'education' => $this->faker->randomElement([
                'B.E/B.Tech', 'M.E/M.Tech', 'MBBS', 'B.Sc', 'M.Sc', 'B.Com', 'M.Com', 'MBA', 'PhD'
            ]),
            'employed_in' => $this->faker->randomElement(['Government', 'Private', 'Self Employed']),
            'occupation' => $this->faker->randomElement([
                'Software Engineer', 'Doctor', 'Civil Engineer', 'Teacher', 'Accountant', 'Business Analyst'
            ]),
            'annual_income' => $this->faker->numberBetween(300000, 2000000) . ' INR',
            'physical_status' => $this->faker->randomElement(['normal', 'physically_challenged']),
            'family_status' => $this->faker->randomElement(['middle_class']),
            'family_type' => $this->faker->randomElement(['joint_family', 'nuclear_family']),
            'about_me' => $this->faker->paragraph,
            'dosham' => $this->faker->randomElement(['yes', 'no', 'donot_know']),
            'star_nakshatram' => $this->faker->randomElement([
                'Ashwini', 'Bharani', 'Krittika', 'Rohini', 'Mrigashirsha', 'Ardra', 'Punarvasu', 
                'Pushya', 'Ashlesha', 'Magha', 'Purva Phalguni', 'Uttara Phalguni', 'Hasta', 'Chitra', 
                'Swati', 'Vishakha', 'Anuradha', 'Jyeshtha', 'Mula', 'Purva Ashadha', 'Uttara Ashadha', 
                'Shravana', 'Dhanishta', 'Shatabhisha', 'Purva Bhadrapada', 'Uttara Bhadrapada', 'Revati'
            ]),
            'rasi' => $this->faker->randomElement([
                'Mesha (Aries)', 'Vrishabha (Taurus)', 'Mithuna (Gemini)', 'Karka (Cancer)',
                'Simha (Leo)', 'Kanya (Virgo)', 'Tula (Libra)', 'Vrischika (Scorpio)',
                'Dhanu (Sagittarius)', 'Makara (Capricorn)', 'Kumbha (Aquarius)', 'Meena (Pisces)'
            ]),
            'gothram' => $this->faker->randomElement(['Vasishta', 'Bharadwaj', 'Kashyapa', 'Gautama']),
            'time_of_birth' => $this->faker->time('H:i'),
            'country_of_birth' => 'India',
            'state_of_birth' => 'Tamil Nadu',
            'city_of_birth' => $this->faker->randomElement([
                'Chennai', 'Coimbatore', 'Madurai', 'Trichy', 'Salem', 'Tirunelveli', 'Erode', 'Tirupur'
            ]),
            'horoscope_chart_style' => $this->faker->randomElement(['North Indian', 'South Indian', 'Other']),
        ];
        
    }
}
