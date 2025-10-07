<?php

namespace ChurchPanel\Events\Database\Seeders;

use ChurchPanel\CpCore\Models\Church;
use ChurchPanel\Events\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = Church::all();

        if ($churches->isEmpty()) {
            $this->command->warn('No churches found. Please run ChurchSeeder first.');
            return;
        }

        $serviceTypes = [
            [
                'name' => 'Sunday Service',
                'description' => 'Main Sunday worship service',
                'color' => '#3B82F6', // Blue
                'icon' => 'heroicon-o-calendar',
                'default_duration_minutes' => 120,
                'display_order' => 1,
            ],
            [
                'name' => 'Prayer Meeting',
                'description' => 'Mid-week prayer and intercession',
                'color' => '#8B5CF6', // Purple
                'icon' => 'heroicon-o-hand-raised',
                'default_duration_minutes' => 60,
                'display_order' => 2,
            ],
            [
                'name' => 'Bible Study',
                'description' => 'In-depth Bible study and discussion',
                'color' => '#10B981', // Green
                'icon' => 'heroicon-o-book-open',
                'default_duration_minutes' => 90,
                'display_order' => 3,
            ],
            [
                'name' => 'Youth Service',
                'description' => 'Service specifically for young people',
                'color' => '#F59E0B', // Amber
                'icon' => 'heroicon-o-user-group',
                'default_duration_minutes' => 120,
                'display_order' => 4,
            ],
            [
                'name' => 'Children\'s Ministry',
                'description' => 'Services and activities for children',
                'color' => '#EC4899', // Pink
                'icon' => 'heroicon-o-sparkles',
                'default_duration_minutes' => 90,
                'display_order' => 5,
            ],
            [
                'name' => 'Men\'s Fellowship',
                'description' => 'Fellowship and ministry for men',
                'color' => '#6366F1', // Indigo
                'icon' => 'heroicon-o-users',
                'default_duration_minutes' => 90,
                'display_order' => 6,
            ],
            [
                'name' => 'Women\'s Fellowship',
                'description' => 'Fellowship and ministry for women',
                'color' => '#DB2777', // Pink
                'icon' => 'heroicon-o-heart',
                'default_duration_minutes' => 90,
                'display_order' => 7,
            ],
            [
                'name' => 'Worship Night',
                'description' => 'Evening of worship and praise',
                'color' => '#14B8A6', // Teal
                'icon' => 'heroicon-o-musical-note',
                'default_duration_minutes' => 120,
                'display_order' => 8,
            ],
            [
                'name' => 'Evangelism Outreach',
                'description' => 'Community outreach and evangelism',
                'color' => '#EF4444', // Red
                'icon' => 'heroicon-o-megaphone',
                'default_duration_minutes' => 180,
                'display_order' => 9,
            ],
            [
                'name' => 'Leadership Meeting',
                'description' => 'Meeting for church leadership',
                'color' => '#6B7280', // Gray
                'icon' => 'heroicon-o-briefcase',
                'default_duration_minutes' => 120,
                'display_order' => 10,
            ],
        ];

        foreach ($churches as $church) {
            foreach ($serviceTypes as $type) {
                ServiceType::create(array_merge($type, [
                    'church_id' => $church->id,
                    'is_active' => true,
                ]));
            }
        }

        $this->command->info('Created ' . (count($serviceTypes) * $churches->count()) . ' service types for ' . $churches->count() . ' church(es).');
    }
}
