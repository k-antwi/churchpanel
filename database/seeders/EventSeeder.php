<?php

namespace Database\Seeders;

use App\Models\User;
use ChurchPanel\CpCore\Models\Branch;
use ChurchPanel\Events\Models\Event;
use ChurchPanel\Events\Models\ServiceType;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->command->warn('No branches found. Please create branches first.');
            return;
        }

        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Coordinators will not be assigned.');
        }

        $eventsCreated = 0;

        foreach ($branches as $branch) {
            $serviceTypes = ServiceType::where('branch_id', $branch->id)
                ->where('is_active', true)
                ->get();

            if ($serviceTypes->isEmpty()) {
                $this->command->warn("No service types found for branch {$branch->name}. Skipping...");
                continue;
            }

            // Create upcoming events for each service type
            foreach ($serviceTypes as $serviceType) {
                // Create 3 upcoming events for each service type
                for ($i = 1; $i <= 3; $i++) {
                    $startDate = now()->addWeeks($i)->setTime(10, 0, 0);
                    $endDate = (clone $startDate)->addMinutes($serviceType->default_duration_minutes);

                    Event::create([
                        'branch_id' => $branch->id,
                        'service_type_id' => $serviceType->id,
                        'title' => $serviceType->name . ' - Week ' . $i,
                        'description' => 'Join us for ' . strtolower($serviceType->description),
                        'location' => $branch->address ?? 'Main Sanctuary',
                        'start_datetime' => $startDate,
                        'end_datetime' => $endDate,
                        'expected_attendees' => rand(50, 200),
                        'coordinator_id' => $users->isNotEmpty() ? $users->random()->id : null,
                        'status' => 'scheduled',
                        'notes' => null,
                        'settings' => null,
                    ]);

                    $eventsCreated++;
                }

                // Create one recurring weekly event
                $recurringStart = now()->addWeeks(4)->setTime(10, 0, 0);
                $recurringEnd = (clone $recurringStart)->addMinutes($serviceType->default_duration_minutes);

                Event::create([
                    'branch_id' => $branch->id,
                    'service_type_id' => $serviceType->id,
                    'title' => 'Weekly ' . $serviceType->name,
                    'description' => 'Our regular weekly ' . strtolower($serviceType->description),
                    'location' => $branch->address ?? 'Main Sanctuary',
                    'start_datetime' => $recurringStart,
                    'end_datetime' => $recurringEnd,
                    'recurrence_pattern' => [
                        'type' => 'weekly',
                        'days' => [0], // Sunday
                        'interval' => 1,
                    ],
                    'recurrence_ends_at' => now()->addMonths(6),
                    'expected_attendees' => rand(100, 300),
                    'coordinator_id' => $users->isNotEmpty() ? $users->random()->id : null,
                    'status' => 'scheduled',
                    'notes' => 'Recurring weekly event',
                    'settings' => [
                        'send_reminders' => true,
                        'allow_registration' => true,
                    ],
                ]);

                $eventsCreated++;
            }

            // Create some past events
            $pastServiceType = $serviceTypes->random();
            for ($i = 1; $i <= 5; $i++) {
                $startDate = now()->subWeeks($i)->setTime(10, 0, 0);
                $endDate = (clone $startDate)->addMinutes($pastServiceType->default_duration_minutes);

                Event::create([
                    'branch_id' => $branch->id,
                    'service_type_id' => $pastServiceType->id,
                    'title' => $pastServiceType->name . ' - Past Event',
                    'description' => 'Past event: ' . strtolower($pastServiceType->description),
                    'location' => $branch->address ?? 'Main Sanctuary',
                    'start_datetime' => $startDate,
                    'end_datetime' => $endDate,
                    'expected_attendees' => rand(50, 200),
                    'coordinator_id' => $users->isNotEmpty() ? $users->random()->id : null,
                    'status' => 'completed',
                    'notes' => 'Event completed successfully',
                ]);

                $eventsCreated++;
            }
        }

        $this->command->info("Created {$eventsCreated} events for {$branches->count()} branch(es).");
    }
}
