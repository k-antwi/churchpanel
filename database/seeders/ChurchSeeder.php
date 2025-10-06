<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = [
            [
                'name' => 'Grace Community Church',
                'description' => 'A vibrant community of believers dedicated to worship, fellowship, and service.',
                'email' => 'info@gracecommunitychurch.org',
                'phone' => '+1 (555) 123-4567',
                'website' => 'https://www.gracecommunitychurch.org',
                'address' => '123 Main Street',
                'city' => 'Springfield',
                'state' => 'IL',
                'country' => 'United States',
                'postal_code' => '62701',
                'latitude' => 39.7817,
                'longitude' => -89.6501,
                'is_active' => true,
                'social_media' => [
                    'facebook' => 'https://facebook.com/gracecommunitychurch',
                    'twitter' => 'https://twitter.com/gracechurch',
                    'instagram' => 'https://instagram.com/gracechurch',
                ],
                'service_times' => [
                    'sunday_morning' => '9:00 AM & 11:00 AM',
                    'sunday_evening' => '6:00 PM',
                    'wednesday' => '7:00 PM',
                ],
            ],
            [
                'name' => 'First Baptist Church',
                'description' => 'Proclaiming the Gospel and making disciples for over 100 years.',
                'email' => 'contact@firstbaptist.org',
                'phone' => '+1 (555) 234-5678',
                'website' => 'https://www.firstbaptist.org',
                'address' => '456 Church Avenue',
                'city' => 'Dallas',
                'state' => 'TX',
                'country' => 'United States',
                'postal_code' => '75201',
                'latitude' => 32.7767,
                'longitude' => -96.7970,
                'is_active' => true,
                'social_media' => [
                    'facebook' => 'https://facebook.com/firstbaptistdallas',
                    'youtube' => 'https://youtube.com/firstbaptist',
                ],
                'service_times' => [
                    'sunday_morning' => '10:30 AM',
                    'sunday_school' => '9:15 AM',
                ],
            ],
            [
                'name' => 'New Life Fellowship',
                'description' => 'Experience God\'s love and transforming power in a welcoming environment.',
                'email' => 'welcome@newlifefellowship.com',
                'phone' => '+1 (555) 345-6789',
                'website' => 'https://www.newlifefellowship.com',
                'address' => '789 Hope Boulevard',
                'city' => 'Phoenix',
                'state' => 'AZ',
                'country' => 'United States',
                'postal_code' => '85001',
                'latitude' => 33.4484,
                'longitude' => -112.0740,
                'is_active' => true,
                'social_media' => [
                    'facebook' => 'https://facebook.com/newlifefellowship',
                    'instagram' => 'https://instagram.com/newlifephx',
                ],
                'service_times' => [
                    'sunday' => '10:00 AM',
                    'saturday' => '6:00 PM',
                ],
            ],
            [
                'name' => 'Trinity Chapel',
                'description' => 'A Spirit-filled church passionate about worship and community outreach.',
                'email' => 'info@trinitychapel.org',
                'phone' => '+1 (555) 456-7890',
                'website' => 'https://www.trinitychapel.org',
                'address' => '321 Faith Street',
                'city' => 'Atlanta',
                'state' => 'GA',
                'country' => 'United States',
                'postal_code' => '30301',
                'latitude' => 33.7490,
                'longitude' => -84.3880,
                'is_active' => true,
                'social_media' => [
                    'facebook' => 'https://facebook.com/trinitychapel',
                    'twitter' => 'https://twitter.com/trinitychapel',
                    'instagram' => 'https://instagram.com/trinitychapel',
                ],
                'service_times' => [
                    'sunday' => '9:00 AM & 11:30 AM',
                    'friday' => '7:30 PM',
                ],
            ],
            [
                'name' => 'Cornerstone Church',
                'description' => 'Building lives on the solid foundation of Jesus Christ.',
                'email' => 'hello@cornerstonechurch.com',
                'phone' => '+1 (555) 567-8901',
                'website' => 'https://www.cornerstonechurch.com',
                'address' => '555 Rock Road',
                'city' => 'Seattle',
                'state' => 'WA',
                'country' => 'United States',
                'postal_code' => '98101',
                'latitude' => 47.6062,
                'longitude' => -122.3321,
                'is_active' => true,
                'social_media' => [
                    'facebook' => 'https://facebook.com/cornerstonechurch',
                    'youtube' => 'https://youtube.com/cornerstone',
                ],
                'service_times' => [
                    'sunday' => '10:00 AM',
                    'midweek' => 'Wednesday 7:00 PM',
                ],
            ],
        ];

        foreach ($churches as $church) {
            \App\Models\Church::create($church);
        }
    }
}
