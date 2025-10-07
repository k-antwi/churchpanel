<?php

namespace Database\Seeders;

use App\Models\User;
use ChurchPanel\Events\Models\Event;
use ChurchPanel\Events\Models\EventAttendance;
use ChurchPanel\People\Models\Contact;
use ChurchPanel\People\Models\Person;
use Illuminate\Database\Seeder;

class EventAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventCount = Event::count();

        if ($eventCount === 0) {
            $this->command->warn('No events found. Please create events first.');
            return;
        }

        $peopleIds = Person::pluck('id')->toArray();
        $contactIds = Contact::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        if (empty($peopleIds) && empty($contactIds)) {
            $this->command->warn('No people or contacts found. Cannot create attendance records.');
            return;
        }

        $attendanceCreated = 0;
        $checkInMethods = ['manual', 'qr_code', 'nfc', 'app'];

        // Process events in chunks to avoid memory issues
        Event::chunk(50, function ($events) use (&$attendanceCreated, $checkInMethods, $peopleIds, $contactIds, $userIds) {
            foreach ($events as $event) {
                // Determine number of attendees based on event status (reduced for memory)
                $attendeeCount = match ($event->status) {
                    'completed' => rand(10, 30),
                    'in_progress' => rand(5, 20),
                    'scheduled' => rand(0, 15), // Some may have pre-registered
                    'cancelled' => 0,
                    default => 0,
                };

                // Get random people and contacts for this event
                $attendees = [];

                // Add people (70% of attendees)
                $peopleCount = (int) ($attendeeCount * 0.7);
                if (!empty($peopleIds) && $peopleCount > 0) {
                    $selectedPeopleIds = array_rand(array_flip($peopleIds), min($peopleCount, count($peopleIds)));
                    if (!is_array($selectedPeopleIds)) {
                        $selectedPeopleIds = [$selectedPeopleIds];
                    }
                    foreach ($selectedPeopleIds as $personId) {
                        $attendees[] = [
                            'type' => Person::class,
                            'id' => $personId,
                            'is_first_timer' => false,
                        ];
                    }
                }

                // Add contacts (30% of attendees, some are first-timers)
                $contactsCount = $attendeeCount - count($attendees);
                if (!empty($contactIds) && $contactsCount > 0) {
                    $selectedContactIds = array_rand(array_flip($contactIds), min($contactsCount, count($contactIds)));
                    if (!is_array($selectedContactIds)) {
                        $selectedContactIds = [$selectedContactIds];
                    }
                    foreach ($selectedContactIds as $contactId) {
                        $attendees[] = [
                            'type' => Contact::class,
                            'id' => $contactId,
                            'is_first_timer' => rand(0, 100) < 30, // 30% chance of being first-timer
                        ];
                    }
                }

                // Create attendance records
                foreach ($attendees as $attendee) {
                    $isPresent = $event->status === 'completed' ? (rand(0, 100) < 85) : (rand(0, 100) < 70); // 85% present for completed, 70% for others

                    $status = $isPresent
                        ? (rand(0, 100) < 90 ? 'present' : 'late') // 90% present, 10% late
                        : (rand(0, 100) < 80 ? 'absent' : 'excused'); // 80% absent, 20% excused

                    $checkInTime = null;
                    $checkOutTime = null;

                    if (in_array($status, ['present', 'late'])) {
                        $checkInTime = $status === 'late'
                            ? $event->start_datetime->copy()->addMinutes(rand(10, 30))
                            : $event->start_datetime->copy()->subMinutes(rand(0, 15));

                        // 60% chance of having check-out time for completed events
                        if ($event->status === 'completed' && rand(0, 100) < 60) {
                            $checkOutTime = $event->end_datetime->copy()->addMinutes(rand(-10, 10));
                        }
                    }

                    $broughtBy = null;
                    if ($attendee['is_first_timer'] && !empty($peopleIds)) {
                        // Assign a random person as the one who brought them
                        $broughtBy = $peopleIds[array_rand($peopleIds)];
                    }

                    EventAttendance::create([
                        'event_id' => $event->id,
                        'attendanceable_type' => $attendee['type'],
                        'attendanceable_id' => $attendee['id'],
                        'attendance_status' => $status,
                        'check_in_time' => $checkInTime,
                        'check_out_time' => $checkOutTime,
                        'checked_in_by' => !empty($userIds) ? $userIds[array_rand($userIds)] : null,
                        'check_in_method' => $checkInMethods[array_rand($checkInMethods)],
                        'notes' => $attendee['is_first_timer'] ? 'First time visitor' : null,
                        'first_time_visitor' => $attendee['is_first_timer'],
                        'brought_by' => $broughtBy,
                    ]);

                    $attendanceCreated++;
                }
            }
        });

        $this->command->info("Created {$attendanceCreated} attendance records for {$eventCount} event(s).");
    }
}
