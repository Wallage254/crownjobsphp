<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create categories
        \App\Models\Category::create([
            'name' => 'Construction',
            'description' => 'Building and construction opportunities',
            'is_active' => true
        ]);

        \App\Models\Category::create([
            'name' => 'Healthcare',
            'description' => 'Medical and healthcare positions',
            'is_active' => true
        ]);

        \App\Models\Category::create([
            'name' => 'Hospitality',
            'description' => 'Hotel and restaurant services',
            'is_active' => true
        ]);

        \App\Models\Category::create([
            'name' => 'Engineering',
            'description' => 'Technical and engineering roles',
            'is_active' => true
        ]);

        // Create sample jobs
        \App\Models\Job::create([
            'title' => 'Construction Site Manager',
            'company' => 'BuildCorp UK',
            'category' => 'Construction',
            'location' => 'London',
            'description' => 'We are seeking an experienced Construction Site Manager to oversee residential construction projects in London. The role involves managing teams, ensuring safety compliance, and coordinating with contractors.',
            'requirements' => 'Minimum 5 years experience in construction management. CSCS card required. Strong leadership and communication skills.',
            'salary_min' => 45000,
            'salary_max' => 65000,
            'job_type' => 'Full-time',
            'is_urgent' => true,
            'visa_sponsored' => true
        ]);

        \App\Models\Job::create([
            'title' => 'Registered Nurse',
            'company' => 'NHS Trust Manchester',
            'category' => 'Healthcare',
            'location' => 'Manchester',
            'description' => 'Join our dedicated healthcare team as a Registered Nurse. You will provide high-quality patient care in a busy hospital environment.',
            'requirements' => 'Valid nursing registration. Minimum 2 years clinical experience. Excellent patient care skills.',
            'salary_min' => 28000,
            'salary_max' => 38000,
            'job_type' => 'Full-time',
            'is_urgent' => false,
            'visa_sponsored' => true
        ]);

        // Create testimonials
        \App\Models\Testimonial::create([
            'name' => 'Kwame Asante',
            'country' => 'Ghana',
            'rating' => 5,
            'comment' => 'CrownOpportunities helped me secure my dream job in London. The process was smooth and the support was excellent!',
            'is_active' => true
        ]);

        \App\Models\Testimonial::create([
            'name' => 'Aisha Mohammed',
            'country' => 'Nigeria',
            'rating' => 5,
            'comment' => 'Professional service and genuine opportunities. I am now working as a nurse in Manchester thanks to CrownOpportunities.',
            'is_active' => true
        ]);
    }
}
