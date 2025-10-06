<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use ChurchPanel\EvangelismCampaign\Models\Visitation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitationSeeder extends Seeder
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

        $purposes = [
            'Initial home visit',
            'Follow-up visit',
            'Prayer visit',
            'Counseling visit',
            'Welcome new convert',
            'Check-in visit',
            'Pastoral care',
            'Hospital visit follow-up',
        ];

        $attendanceStatuses = ['home', 'not_home', 'moved'];

        $prayerRequestsExamples = [
            'Prayer for healing from illness',
            'Prayer for job opportunity',
            'Prayer for family reconciliation',
            'Prayer for financial breakthrough',
            'Prayer for spiritual growth',
            'Prayer for children\'s education',
            'Prayer for guidance in decision making',
            null,
        ];

        $needsExamples = [
            'Financial assistance needed',
            'Needs Bible study materials',
            'Needs transportation to church',
            'Needs counseling support',
            'Needs prayer partners',
            'Needs mentorship',
            null,
        ];

        foreach ($contacts->random(min(60, $contacts->count())) as $contact) {
            $visitCount = rand(1, 4);

            for ($i = 0; $i < $visitCount; $i++) {
                $visitDate = fake()->dateTimeBetween('-3 months', 'now');
                $attendanceStatus = fake()->randomElement($attendanceStatuses);
                $wasHome = $attendanceStatus === 'home';

                Visitation::create([
                    'contact_id' => $contact->id,
                    'visited_by' => $users->random()->id,
                    'visit_date' => $visitDate,
                    'purpose' => fake()->randomElement($purposes),
                    'duration_minutes' => $wasHome ? fake()->numberBetween(15, 120) : null,
                    'attendance_status' => $attendanceStatus,
                    'notes' => $wasHome ? fake()->optional()->paragraph() : fake()->optional()->sentence(),
                    'prayer_requests' => $wasHome ? fake()->randomElement($prayerRequestsExamples) : null,
                    'needs_identified' => $wasHome ? fake()->randomElement($needsExamples) : null,
                    'follow_up_required' => $wasHome ? fake()->boolean(40) : fake()->boolean(80),
                ]);
            }
        }

        $this->command->info('Visitations seeded successfully!');
    }
}
