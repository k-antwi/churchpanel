<?php

namespace Database\Seeders;

use ChurchPanel\CpCore\Models\Branch;
use ChurchPanel\CpCore\Models\Church;
use ChurchPanel\People\Models\Contact;
use App\Models\User;
use ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class EvangelismCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = Church::all();
        $users = User::all();
        $roles = Role::all();

        if ($churches->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please seed churches and users first.');
            return;
        }

        $types = ['outreach', 'crusade', 'visitation', 'door_to_door', 'online'];
        $statuses = ['planning', 'active', 'completed'];

        $campaigns = [
            [
                'title' => 'City-Wide Evangelism Outreach',
                'description' => 'A comprehensive outreach program targeting the entire city with the gospel message.',
                'type' => 'outreach',
                'location' => 'Downtown City Center',
                'target_souls' => 500,
                'budget' => 10000.00,
                'status' => 'active',
            ],
            [
                'title' => 'Revival Crusade 2025',
                'description' => 'Three-day revival crusade featuring guest speakers and worship sessions.',
                'type' => 'crusade',
                'location' => 'City Stadium',
                'target_souls' => 1000,
                'budget' => 25000.00,
                'status' => 'planning',
            ],
            [
                'title' => 'Door-to-Door Ministry',
                'description' => 'Weekly door-to-door evangelism in local neighborhoods.',
                'type' => 'door_to_door',
                'location' => 'Various Neighborhoods',
                'target_souls' => 200,
                'budget' => 2000.00,
                'status' => 'active',
            ],
            [
                'title' => 'Hospital Visitation Program',
                'description' => 'Visiting patients in local hospitals to share hope and pray.',
                'type' => 'visitation',
                'location' => 'City General Hospital',
                'target_souls' => 100,
                'budget' => 1000.00,
                'status' => 'active',
            ],
            [
                'title' => 'Online Gospel Campaign',
                'description' => 'Digital evangelism campaign through social media and online events.',
                'type' => 'online',
                'location' => 'Virtual',
                'target_souls' => 2000,
                'budget' => 5000.00,
                'status' => 'planning',
            ],
        ];

        foreach ($campaigns as $campaignData) {
            $church = $churches->random();
            $branch = Branch::where('church_id', $church->id)->inRandomOrder()->first();

            $campaign = EvangelismCampaign::create([
                'church_id' => $church->id,
                'branch_id' => $branch?->id,
                'title' => $campaignData['title'],
                'description' => $campaignData['description'],
                'type' => $campaignData['type'],
                'location' => $campaignData['location'],
                'start_date' => fake()->dateTimeBetween('now', '+1 month'),
                'end_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
                'target_souls' => $campaignData['target_souls'],
                'coordinator_id' => $users->random()->id,
                'budget' => $campaignData['budget'],
                'status' => $campaignData['status'],
            ]);

            // Attach team members
            $teamMemberCount = min(rand(3, 7), $users->count());
            if ($teamMemberCount > 0) {
                $teamMembers = $users->random($teamMemberCount);
                foreach ($teamMembers as $member) {
                    $campaign->teamMembers()->attach($member->id, [
                        'role_id' => $roles->isNotEmpty() ? $roles->random()->id : null,
                    ]);
                }
            }

            // Attach contacts if campaign is active or completed
            if (in_array($campaignData['status'], ['active', 'completed'])) {
                $contacts = Contact::inRandomOrder()->limit(rand(5, 15))->get();
                $campaign->contacts()->attach($contacts->pluck('id'));
            }
        }
    }
}
