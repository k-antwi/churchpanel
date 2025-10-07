<?php

namespace Database\Seeders;

use App\Models\User;
use ChurchPanel\CpCore\Models\Contact;
use ChurchPanel\People\Models\Person;
use ChurchPanel\People\Models\WellbeingRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WellbeingRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $people = Person::all();
        $contacts = Contact::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }

        if ($people->isEmpty() && $contacts->isEmpty()) {
            $this->command->warn('No people or contacts found. Please run PersonSeeder and ContactSeeder first.');
            return;
        }

        $types = ['spiritual', 'physical', 'financial', 'emotional'];
        $statuses = ['excellent', 'good', 'fair', 'poor', 'critical'];

        $wellbeingData = [
            [
                'type' => 'spiritual',
                'status' => 'good',
                'prayer_requests' => 'Prayer for spiritual growth and deeper relationship with God. Wants to be more consistent in daily devotions.',
                'needs' => 'Mentorship in spiritual disciplines',
                'assistance_provided' => 'Connected with a spiritual mentor from the church',
                'notes' => 'Shows genuine desire for spiritual growth. Very receptive to guidance.',
            ],
            [
                'type' => 'physical',
                'status' => 'fair',
                'prayer_requests' => 'Healing from recent surgery. Recovery is slower than expected.',
                'needs' => 'Transportation to medical appointments, meal support',
                'assistance_provided' => 'Organized meal train for 2 weeks, arranged rides to appointments',
                'notes' => 'Family is very grateful for church support. Will follow up in 2 weeks.',
            ],
            [
                'type' => 'financial',
                'status' => 'poor',
                'prayer_requests' => 'Prayer for job search and provision. Recently laid off from work.',
                'needs' => 'Job leads, resume assistance, financial counseling',
                'assistance_provided' => 'Connected with career counselor, provided benevolence assistance for rent',
                'notes' => 'Very anxious about financial situation. Needs ongoing support and encouragement.',
            ],
            [
                'type' => 'emotional',
                'status' => 'critical',
                'prayer_requests' => 'Dealing with grief after loss of spouse. Feeling overwhelmed and isolated.',
                'needs' => 'Grief counseling, regular check-ins, social connection',
                'assistance_provided' => 'Connected with grief support group, arranged weekly visits',
                'notes' => 'Requires urgent follow-up. Family lives out of state. Consider daily check-ins.',
            ],
            [
                'type' => 'spiritual',
                'status' => 'excellent',
                'prayer_requests' => 'Thanksgiving for answered prayers. Wants to serve in ministry.',
                'needs' => 'Ministry placement opportunities',
                'assistance_provided' => 'Connected with ministry coordinator for placement',
                'notes' => 'Strong faith and testimony. Would be great small group leader.',
            ],
            [
                'type' => 'physical',
                'status' => 'good',
                'prayer_requests' => 'Maintaining healthy lifestyle changes. Grateful for progress.',
                'needs' => 'Accountability partner for health goals',
                'assistance_provided' => 'Connected with health accountability group',
                'notes' => 'Very motivated and positive attitude.',
            ],
            [
                'type' => 'financial',
                'status' => 'good',
                'prayer_requests' => 'Wisdom in stewardship and debt reduction plan.',
                'needs' => 'Financial planning resources',
                'assistance_provided' => 'Enrolled in financial peace university course',
                'notes' => 'Making good progress on debt reduction.',
            ],
            [
                'type' => 'emotional',
                'status' => 'fair',
                'prayer_requests' => 'Struggling with anxiety and stress from work. Needs peace.',
                'needs' => 'Stress management resources, counseling',
                'assistance_provided' => 'Referred to Christian counselor, provided stress management materials',
                'notes' => 'Work situation is very challenging. May need more intensive support.',
            ],
            [
                'type' => 'spiritual',
                'status' => 'fair',
                'prayer_requests' => 'Struggling with doubt and questions about faith. Seeking answers.',
                'needs' => 'Apologetics resources, mentorship',
                'assistance_provided' => 'Connected with pastor for one-on-one discussions, provided reading materials',
                'notes' => 'Genuine seeker. Needs patient, thoughtful engagement.',
            ],
            [
                'type' => 'physical',
                'status' => 'poor',
                'prayer_requests' => 'Chronic illness management. Pain is affecting daily life.',
                'needs' => 'Practical help with household tasks, prayer support',
                'assistance_provided' => 'Organized volunteer team for weekly household help',
                'notes' => 'Condition is long-term. Will need ongoing support structure.',
            ],
        ];

        foreach ($wellbeingData as $index => $data) {
            // Randomly decide if this record is for a person or contact
            $usePerson = $people->isNotEmpty() && (rand(0, 1) || $contacts->isEmpty());

            if ($usePerson) {
                $recordable = $people->random();
            } else {
                $recordable = $contacts->random();
            }

            WellbeingRecord::create([
                'recorded_by' => $users->random()->id,
                'recordable_id' => $recordable->id,
                'recordable_type' => get_class($recordable),
                'record_date' => now()->subDays(rand(1, 60)),
                'type' => $data['type'],
                'status' => $data['status'],
                'prayer_requests' => $data['prayer_requests'],
                'needs' => $data['needs'],
                'assistance_provided' => $data['assistance_provided'],
                'notes' => $data['notes'],
            ]);
        }

        $this->command->info('Created ' . count($wellbeingData) . ' wellbeing records with relationships.');
    }
}
