<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>User::factory(),
            'title'=>$this->faker->jobTitle(),
            'description'=>$this->faker->paragraphs(2,true),
            'salary'=>$this->faker->numberBetween($min = 40000, $max = 120000),
            'tags'=>implode(', ',$this->faker->words(3)),
            'job_type'=>$this->faker->randomElement(['Full-time','Part-time','Freelance','Contract']),
            'remote'=>$this->faker->boolean(),
            'requirements'=>$this->faker->sentences(3,true),
            'benefits'=>$this->faker->sentences(2,true),
            'address'=>$this->faker->streetAddress(),
            'city'=>$this->faker->city(),
            'state'=>$this->faker->state(),
            'zip_code'=>$this->faker->postcode(),
            'contact_email'=>$this->faker->safeEmail(),
            'contact_phone'=>$this->faker->phoneNumber(),
            'company_name'=>$this->faker->company(),
            'company_description'=>$this->faker->paragraphs(2,true),
            'company_logo'=>$this->faker->imageUrl(100,100,'business',true,'logo'),
            'company_website'=>$this->faker->url()
        ];
    }
}
