<?php

namespace Database\Seeders;

use ChurchPanel\People\Models\Contact;
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

        $stageMaterials = [
            'new_convert' => ['New Believer Booklet', 'Gospel of John', 'Welcome Pack'],
            'baptism_prep' => ['Baptism Guide', 'Water Baptism DVD', 'Testimony Form'],
            'baptized' => ['Certificate of Baptism', 'New Life in Christ Book'],
            'foundation_class' => ['Foundation Course Workbook', 'Bible Study Guide', 'Prayer Journal'],
            'membership_class' => ['Church Covenant', 'Membership Manual', 'Statement of Faith'],
            'maturity_class' => ['Spiritual Growth Curriculum', 'Daily Devotional', 'Bible Reading Plan'],
            'leadership_training' => ['Leadership Handbook', 'Servant Leadership Book', 'Ministry Guidelines'],
            'serving' => ['Ministry Assignment', 'Volunteer Handbook', 'Team Directory'],
        ];

        $journeysCreated = 0;

        // Create journeys for 60% of contacts
        $contactsWithJourneys = $contacts->random(min((int)($contacts->count() * 0.6), $contacts->count()));

        foreach ($contactsWithJourneys as $contact) {
            // Each contact gets 1-3 progressive journey stages
            $numberOfStages = rand(1, 3);
            $contactStages = array_slice($stages, 0, $numberOfStages);

            foreach ($contactStages as $index => $stage) {
                $startedAt = now()->subMonths(rand(1, 24))->subDays(rand(0, 30));

                // 70% of journeys are completed for earlier stages
                $isCompleted = $index < $numberOfStages - 1 || rand(0, 100) < 70;
                $completedAt = $isCompleted
                    ? $startedAt->copy()->addWeeks(rand(4, 12))
                    : null;

                // Build materials given array
                $materialsGiven = [];
                $materials = $stageMaterials[$stage] ?? [];
                foreach ($materials as $material) {
                    $materialsGiven[] = [
                        'material' => $material,
                        'date' => $startedAt->copy()->addDays(rand(0, 7))->format('Y-m-d'),
                    ];
                }

                // Generate stage-specific notes
                $notes = $this->generateNotes($stage, $isCompleted);

                DiscipleshipJourney::create([
                    'contact_id' => $contact->id,
                    'stage' => $stage,
                    'started_at' => $startedAt,
                    'completed_at' => $completedAt,
                    'mentor_id' => $users->random()->id,
                    'notes' => $notes,
                    'materials_given' => $materialsGiven,
                ]);

                $journeysCreated++;
            }
        }

        $this->command->info("Created {$journeysCreated} discipleship journeys for {$contactsWithJourneys->count()} contact(s).");
    }

    private function generateNotes(string $stage, bool $isCompleted): string
    {
        $progressNotes = [
            'new_convert' => [
                'Attended salvation prayer session',
                'Shared testimony with small group',
                'Regular attendance at Sunday services',
                'Completed initial counseling session',
                'Connected with welcome team',
            ],
            'baptism_prep' => [
                'Completed baptism class',
                'Testimony prepared and reviewed',
                'Understanding of water baptism confirmed',
                'Family invited to baptism service',
                'Baptism scheduled',
            ],
            'baptized' => [
                'Water baptism completed',
                'Testimony shared during service',
                'Family members attended',
                'Photos and video taken',
                'Certificate presented',
            ],
            'foundation_class' => [
                'Enrolled in foundation class',
                'Attending weekly sessions',
                'Completing homework assignments',
                'Active in discussion groups',
                'Building friendships with classmates',
            ],
            'membership_class' => [
                'Enrolled in membership class',
                'Reviewed church vision and values',
                'Signed church covenant',
                'Completed membership interview',
                'Joined a life group',
            ],
            'maturity_class' => [
                'Studying spiritual disciplines',
                'Practicing daily devotions',
                'Growing in prayer life',
                'Reading through Bible systematically',
                'Mentoring newer believers',
            ],
            'leadership_training' => [
                'Enrolled in leadership training',
                'Demonstrating servant leadership',
                'Participating in ministry projects',
                'Completing leadership assessments',
                'Shadowing current leaders',
            ],
            'serving' => [
                'Active in ministry team',
                'Leading small group',
                'Serving weekly',
                'Training new volunteers',
                'Making significant impact',
            ],
        ];

        $notes = $progressNotes[$stage] ?? ['Progress recorded'];

        if ($isCompleted) {
            $notes[] = 'Stage completed successfully';
            $notes[] = 'Ready to move to next stage';
        } else {
            $notes[] = 'Currently in progress';
            $notes[] = 'Regular check-ins scheduled';
        }

        return implode('. ', array_slice($notes, 0, rand(3, 5))) . '.';
    }
}
