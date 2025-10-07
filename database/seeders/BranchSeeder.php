<?php

namespace Database\Seeders;

use ChurchPanel\CpCore\Models\Branch;
use ChurchPanel\CpCore\Models\Church;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get churches
        $graceChurch = Church::where('name', 'Grace Community Church')->first();
        $firstBaptist = Church::where('name', 'First Baptist Church')->first();
        $newLife = Church::where('name', 'New Life Fellowship')->first();
        $trinity = Church::where('name', 'Trinity Chapel')->first();
        $cornerstone = Church::where('name', 'Cornerstone Church')->first();

        // Get some users for pastors (if they exist)
        $users = User::limit(5)->get();

        if ($graceChurch) {
            Branch::create([
                'church_id' => $graceChurch->id,
                'name' => 'Main Campus',
                'slug' => 'grace-main-campus',
                'address' => '123 Main Street',
                'city' => 'Springfield',
                'country' => 'United States',
                'phone' => '+1 (555) 123-4567',
                'email' => 'main@gracecommunitychurch.org',
                'pastor_id' => $users->isNotEmpty() ? $users[0]->id : null,
                'is_main' => true,
                'settings' => [
                    'capacity' => 500,
                    'parking_spaces' => 100,
                ],
            ]);

            Branch::create([
                'church_id' => $graceChurch->id,
                'name' => 'North Campus',
                'slug' => 'grace-north-campus',
                'address' => '456 North Avenue',
                'city' => 'Springfield',
                'country' => 'United States',
                'phone' => '+1 (555) 123-4568',
                'email' => 'north@gracecommunitychurch.org',
                'pastor_id' => $users->count() > 1 ? $users[1]->id : null,
                'is_main' => false,
                'settings' => [
                    'capacity' => 300,
                    'parking_spaces' => 50,
                ],
            ]);
        }

        if ($firstBaptist) {
            Branch::create([
                'church_id' => $firstBaptist->id,
                'name' => 'Downtown Campus',
                'slug' => 'first-baptist-downtown',
                'address' => '456 Church Avenue',
                'city' => 'Dallas',
                'country' => 'United States',
                'phone' => '+1 (555) 234-5678',
                'email' => 'downtown@firstbaptist.org',
                'pastor_id' => $users->count() > 2 ? $users[2]->id : null,
                'is_main' => true,
                'settings' => [
                    'capacity' => 1000,
                    'parking_spaces' => 200,
                ],
            ]);
        }

        if ($newLife) {
            Branch::create([
                'church_id' => $newLife->id,
                'name' => 'Central Branch',
                'slug' => 'new-life-central',
                'address' => '789 Hope Boulevard',
                'city' => 'Phoenix',
                'country' => 'United States',
                'phone' => '+1 (555) 345-6789',
                'email' => 'central@newlifefellowship.com',
                'pastor_id' => $users->count() > 3 ? $users[3]->id : null,
                'is_main' => true,
                'settings' => [
                    'capacity' => 400,
                    'parking_spaces' => 80,
                ],
            ]);

            Branch::create([
                'church_id' => $newLife->id,
                'name' => 'East Campus',
                'slug' => 'new-life-east',
                'address' => '123 Sunrise Drive',
                'city' => 'Phoenix',
                'country' => 'United States',
                'phone' => '+1 (555) 345-6790',
                'email' => 'east@newlifefellowship.com',
                'is_main' => false,
                'settings' => [
                    'capacity' => 250,
                    'parking_spaces' => 60,
                ],
            ]);
        }

        if ($trinity) {
            Branch::create([
                'church_id' => $trinity->id,
                'name' => 'Trinity Main',
                'slug' => 'trinity-main',
                'address' => '321 Faith Street',
                'city' => 'Atlanta',
                'country' => 'United States',
                'phone' => '+1 (555) 456-7890',
                'email' => 'main@trinitychapel.org',
                'pastor_id' => $users->count() > 4 ? $users[4]->id : null,
                'is_main' => true,
                'settings' => [
                    'capacity' => 600,
                    'parking_spaces' => 150,
                ],
            ]);
        }

        if ($cornerstone) {
            Branch::create([
                'church_id' => $cornerstone->id,
                'name' => 'Seattle Campus',
                'slug' => 'cornerstone-seattle',
                'address' => '555 Rock Road',
                'city' => 'Seattle',
                'country' => 'United States',
                'phone' => '+1 (555) 567-8901',
                'email' => 'seattle@cornerstonechurch.com',
                'is_main' => true,
                'settings' => [
                    'capacity' => 700,
                    'parking_spaces' => 120,
                ],
            ]);
        }
    }
}
