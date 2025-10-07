<?php

namespace ChurchPanel\CpCore\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = \ChurchPanel\CpCore\Models\Church::all();

        if ($churches->isEmpty()) {
            $this->command->warn('No churches found. Please run ChurchSeeder first.');
            return;
        }

        $people = [
            [
                'title' => 'Rev.',
                'first_name' => 'John',
                'last_name' => 'Anderson',
                'email' => 'john.anderson@example.com',
                'type' => 'pastor',
                'date_of_birth' => '1975-05-15',
                'address_line' => '123 Pastor Lane',
                'town' => 'Springfield',
                'city' => 'Springfield',
                'country' => 'United States',
                'county' => 'Sangamon',
                'postcode' => '62701',
                'mobile_phone' => '+1 (555) 111-2222',
                'phone' => '+1 (555) 333-4444',
                'bio' => 'Senior Pastor with over 20 years of ministry experience.',
                'site' => 'https://johnanderso.com',
            ],
            [
                'title' => 'Mrs.',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'type' => 'member',
                'date_of_birth' => '1982-08-22',
                'address_line' => '456 Church Street',
                'town' => 'Dallas',
                'city' => 'Dallas',
                'country' => 'United States',
                'county' => 'Dallas',
                'postcode' => '75201',
                'mobile_phone' => '+1 (555) 222-3333',
                'bio' => 'Active member and worship team leader.',
            ],
            [
                'title' => 'Mr.',
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael.williams@example.com',
                'type' => 'member',
                'date_of_birth' => '1990-03-10',
                'address_line' => '789 Faith Avenue',
                'town' => 'Phoenix',
                'city' => 'Phoenix',
                'country' => 'United States',
                'county' => 'Maricopa',
                'postcode' => '85001',
                'mobile_phone' => '+1 (555) 444-5555',
                'bio' => 'Youth group coordinator and small group leader.',
            ],
            [
                'title' => 'Dr.',
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'emily.brown@example.com',
                'type' => 'elder',
                'date_of_birth' => '1978-11-30',
                'address_line' => '321 Hope Road',
                'town' => 'Atlanta',
                'city' => 'Atlanta',
                'country' => 'United States',
                'county' => 'Fulton',
                'postcode' => '30301',
                'mobile_phone' => '+1 (555) 666-7777',
                'phone' => '+1 (555) 888-9999',
                'bio' => 'Church elder and counseling ministry leader.',
            ],
            [
                'title' => 'Mr.',
                'first_name' => 'David',
                'last_name' => 'Martinez',
                'email' => 'david.martinez@example.com',
                'type' => 'deacon',
                'date_of_birth' => '1985-07-18',
                'address_line' => '555 Grace Street',
                'town' => 'Seattle',
                'city' => 'Seattle',
                'country' => 'United States',
                'county' => 'King',
                'postcode' => '98101',
                'mobile_phone' => '+1 (555) 123-9876',
                'bio' => 'Deacon serving in outreach and benevolence ministry.',
            ],
            [
                'title' => 'Ms.',
                'first_name' => 'Jennifer',
                'last_name' => 'Davis',
                'email' => 'jennifer.davis@example.com',
                'type' => 'member',
                'date_of_birth' => '1995-12-05',
                'address_line' => '222 Love Lane',
                'town' => 'Springfield',
                'city' => 'Springfield',
                'country' => 'United States',
                'county' => 'Sangamon',
                'postcode' => '62701',
                'mobile_phone' => '+1 (555) 234-5678',
                'bio' => "Children's ministry volunteer and Sunday school teacher.",
            ],
            [
                'title' => 'Pastor',
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'james.wilson@example.com',
                'type' => 'pastor',
                'date_of_birth' => '1972-09-14',
                'address_line' => '444 Peace Boulevard',
                'town' => 'Dallas',
                'city' => 'Dallas',
                'country' => 'United States',
                'county' => 'Dallas',
                'postcode' => '75201',
                'mobile_phone' => '+1 (555) 345-6789',
                'phone' => '+1 (555) 456-7890',
                'bio' => 'Associate Pastor focusing on discipleship and teaching.',
                'site' => 'https://jameswilson.ministry',
            ],
            [
                'title' => 'Mrs.',
                'first_name' => 'Lisa',
                'last_name' => 'Thompson',
                'email' => 'lisa.thompson@example.com',
                'type' => 'member',
                'date_of_birth' => '1988-04-25',
                'address_line' => '666 Joy Circle',
                'town' => 'Phoenix',
                'city' => 'Phoenix',
                'country' => 'United States',
                'county' => 'Maricopa',
                'postcode' => '85001',
                'mobile_phone' => '+1 (555) 567-8901',
                'bio' => 'Prayer ministry coordinator and intercessor.',
            ],
        ];

        // Distribute people among churches
        foreach ($people as $index => $person) {
            $church = $churches[$index % $churches->count()];
            $person['church_id'] = $church->id;
            \ChurchPanel\People\Models\Person::create($person);
        }
    }
}
