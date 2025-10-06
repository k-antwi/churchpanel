<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign;
use ChurchPanel\EvangelismCampaign\Models\FollowUp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = Contact::all();
        $campaigns = EvangelismCampaign::all();
        $users = User::all();

        if ($contacts->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please seed contacts and users first.');
            return;
        }

        $types = ['phone', 'sms', 'email', 'visit'];
        $statuses = ['pending', 'completed', 'cancelled'];

        foreach ($contacts->random(min(50, $contacts->count())) as $contact) {
            $followUpCount = rand(1, 3);

            for ($i = 0; $i < $followUpCount; $i++) {
                $status = fake()->randomElement($statuses);
                $scheduledDate = fake()->dateTimeBetween('-2 months', '+1 month');

                // Only set completed_at if status is completed and scheduled date is in the past
                $completedAt = null;
                if ($status === 'completed' && $scheduledDate <= now()) {
                    $completedAt = fake()->dateTimeBetween($scheduledDate, 'now');
                }

                FollowUp::create([
                    'contact_id' => $contact->id,
                    'evangelism_campaign_id' => $campaigns->isNotEmpty() ? $campaigns->random()->id : null,
                    'assigned_to' => $users->random()->id,
                    'type' => fake()->randomElement($types),
                    'scheduled_date' => $scheduledDate,
                    'completed_at' => $completedAt,
                    'status' => $status,
                    'notes' => fake()->optional()->sentence(),
                    'outcome' => $status === 'completed' ? fake()->sentence() : null,
                    'next_action' => $status === 'completed' ? fake()->optional()->sentence() : null,
                ]);
            }
        }
    }
}
