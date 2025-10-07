<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use ChurchPanel\EvangelismCampaign\Models\DiscipleshipJourney;
use Illuminate\Database\Seeder;

class DiscipleshipJourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = Contact::all();
        $users = User::all();

        if ($contacts->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please seed contacts and users first.');
            return;
        }

        $stages = [
            'new_convert',
            'baptism_prep',
            'baptized',
            'foundation_class',
            'membership_class',
            'maturity_class',
            'leadership_training',
            'serving'
        ];

        $materials = [
            'New Believer\'s Guide',
            'Baptism Class Workbook',
            'Foundation Course Materials',
            'Membership Manual',
            'Spiritual Growth Book',
            'Leadership Training Guide',
            'Ministry Handbook',
        ];

        foreach ($contacts->random(min(30, $contacts->count())) as $contact) {
            $journeyCount = rand(1, 3);

            for ($i = 0; $i < $journeyCount; $i++) {
                $startedAt = fake()->dateTimeBetween('-1 year', 'now');
                $isCompleted = fake()->boolean(60);

                $materialsGiven = [];
                $materialCount = rand(1, 3);
                for ($j = 0; $j < $materialCount; $j++) {
                    $materialsGiven[] = [
                        'material' => fake()->randomElement($materials),
                        'date' => fake()->dateTimeBetween($startedAt, 'now')->format('Y-m-d'),
                    ];
                }

                DiscipleshipJourney::create([
                    'contact_id' => $contact->id,
                    'stage' => fake()->randomElement($stages),
                    'started_at' => $startedAt,
                    'completed_at' => $isCompleted ? fake()->dateTimeBetween($startedAt, 'now') : null,
                    'mentor_id' => $users->random()->id,
                    'notes' => fake()->optional()->paragraph(),
                    'materials_given' => $materialsGiven,
                ]);
            }
        }
    }
}
