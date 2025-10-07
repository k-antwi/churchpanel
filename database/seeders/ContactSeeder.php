<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Church;
use App\Models\Contact;
use App\Models\User;
use ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign;
use ChurchPanel\People\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = Church::all();
        $people = Person::all();
        $users = User::all();
        $campaigns = EvangelismCampaign::all();

        if ($churches->isEmpty() || $people->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please seed churches, people, and users first.');
            return;
        }

        $stages = ['prospect', 'new_convert', 'believer', 'member', 'graduated'];
        $ageGroups = ['18-25', '26-35', '36-50', '51-65', '65+'];
        $maritalStatuses = ['single', 'married', 'divorced', 'widowed'];
        $contactSources = ['church_service', 'event', 'referral', 'website', 'social_media', 'outreach'];

        $occupations = [
            'Teacher', 'Engineer', 'Doctor', 'Nurse', 'Accountant', 'Business Owner',
            'Software Developer', 'Sales Manager', 'Student', 'Retired', 'Self-employed'
        ];

        // Create contacts linked to existing people
        foreach ($people->take(20) as $person) {
            $church = $churches->random();
            $branch = Branch::where('church_id', $church->id)->inRandomOrder()->first();

            Contact::create([
                'church_id' => $church->id,
                'branch_id' => $branch?->id,
                'evangelism_campaign_id' => $campaigns->isNotEmpty() && fake()->boolean(60) ? $campaigns->random()->id : null,
                'person_id' => $person->id,
                'first_name' => $person->first_name,
                'last_name' => $person->last_name,
                'email' => $person->email,
                'mobile' => $person->mobile_phone,
                'address' => $person->address_line,
                'social_handle' => fake()->optional()->userName(),
                'age_group' => fake()->randomElement($ageGroups),
                'marital_status' => fake()->randomElement($maritalStatuses),
                'occupation' => fake()->randomElement($occupations),
                'contact_source' => fake()->randomElement($contactSources),
                'notes' => fake()->optional()->sentence(),
                'captured_by' => $users->random()->id,
                'captured_at' => fake()->dateTimeBetween('-6 months', 'now'),
                'stage' => fake()->randomElement($stages),
            ]);
        }

        // Create contacts without person_id (standalone contacts)
        for ($i = 0; $i < 30; $i++) {
            $church = $churches->random();
            $branch = Branch::where('church_id', $church->id)->inRandomOrder()->first();

            Contact::create([
                'church_id' => $church->id,
                'branch_id' => $branch?->id,
                'evangelism_campaign_id' => $campaigns->isNotEmpty() && fake()->boolean(70) ? $campaigns->random()->id : null,
                'person_id' => null,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->optional()->email(),
                'mobile' => fake()->optional()->phoneNumber(),
                'address' => fake()->optional()->address(),
                'social_handle' => fake()->optional()->userName(),
                'age_group' => fake()->randomElement($ageGroups),
                'marital_status' => fake()->randomElement($maritalStatuses),
                'occupation' => fake()->randomElement($occupations),
                'contact_source' => fake()->randomElement($contactSources),
                'notes' => fake()->optional()->sentence(),
                'captured_by' => $users->random()->id,
                'captured_at' => fake()->dateTimeBetween('-6 months', 'now'),
                'stage' => fake()->randomElement(array_diff($stages, ['graduated'])), // Don't auto-graduate new contacts
            ]);
        }
    }
}
