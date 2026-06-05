<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full system control']);
        $managerRole = Role::create(['name' => 'Manager', 'slug' => 'manager', 'description' => 'Reports and monitoring']);
        $receptionistRole = Role::create(['name' => 'Receptionist', 'slug' => 'receptionist', 'description' => 'Front desk operations']);
        $guestRole = Role::create(['name' => 'Guest', 'slug' => 'guest', 'description' => 'Regular customer']);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bungestay.com',
            'phone' => '+254700000001',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@bungestay.com',
            'phone' => '+254700000002',
            'password' => bcrypt('password'),
            'role_id' => $managerRole->id,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@bungestay.com',
            'phone' => '+254700000003',
            'password' => bcrypt('password'),
            'role_id' => $receptionistRole->id,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Guest',
            'email' => 'guest@bungestay.com',
            'phone' => '+254700000004',
            'password' => bcrypt('password'),
            'role_id' => $guestRole->id,
            'email_verified_at' => now(),
        ]);

        $single = RoomType::create(['name' => 'Single Room', 'slug' => 'single-room', 'description' => 'Cozy room for one person']);
        $double = RoomType::create(['name' => 'Double Room', 'slug' => 'double-room', 'description' => 'Spacious room for two']);
        $suite = RoomType::create(['name' => 'Suite', 'slug' => 'suite', 'description' => 'Luxury suite with premium amenities']);
        $family = RoomType::create(['name' => 'Family Room', 'slug' => 'family-room', 'description' => 'Large room for families']);

        $rooms = [
            ['name' => 'Standard Single', 'room_type_id' => $single->id, 'description' => 'A comfortable single room with essential amenities.', 'price_per_night' => 125000, 'capacity' => 1, 'size_sqft' => 200, 'amenities' => ['WiFi', 'TV', 'Air Conditioning']],
            ['name' => 'Deluxe Single', 'room_type_id' => $single->id, 'description' => 'Premium single room with city view.', 'price_per_night' => 187500, 'capacity' => 1, 'size_sqft' => 250, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar']],
            ['name' => 'Standard Double', 'room_type_id' => $double->id, 'description' => 'Comfortable double room with queen bed.', 'price_per_night' => 225000, 'capacity' => 2, 'size_sqft' => 300, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Work Desk']],
            ['name' => 'Deluxe Double', 'room_type_id' => $double->id, 'description' => 'Spacious double room with king bed and balcony.', 'price_per_night' => 300000, 'capacity' => 2, 'size_sqft' => 350, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Balcony']],
            ['name' => 'Executive Suite', 'room_type_id' => $suite->id, 'description' => 'Luxury suite with separate living area.', 'price_per_night' => 500000, 'capacity' => 2, 'size_sqft' => 500, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Living Room', 'Jacuzzi']],
            ['name' => 'Presidential Suite', 'room_type_id' => $suite->id, 'description' => 'The ultimate luxury experience with panoramic views.', 'price_per_night' => 875000, 'capacity' => 4, 'size_sqft' => 800, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Living Room', 'Jacuzzi', 'Kitchen', 'Butler Service']],
            ['name' => 'Family Room', 'room_type_id' => $family->id, 'description' => 'Perfect for families with connecting rooms.', 'price_per_night' => 375000, 'capacity' => 4, 'size_sqft' => 450, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Kitchen', 'Play Area']],
            ['name' => 'Family Suite', 'room_type_id' => $family->id, 'description' => 'Large family suite with two bedrooms.', 'price_per_night' => 550000, 'capacity' => 6, 'size_sqft' => 650, 'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Kitchen', 'Living Room', 'Balcony']],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $services = [
            ['name' => 'Laundry Service', 'slug' => 'laundry', 'description' => 'Professional laundry and dry cleaning', 'price' => 37500, 'category' => 'Housekeeping'],
            ['name' => 'Breakfast', 'slug' => 'breakfast', 'description' => 'Continental breakfast buffet', 'price' => 30000, 'category' => 'Dining'],
            ['name' => 'Lunch', 'slug' => 'lunch', 'description' => 'Full course lunch', 'price' => 45000, 'category' => 'Dining'],
            ['name' => 'Dinner', 'slug' => 'dinner', 'description' => 'Three-course dinner', 'price' => 62500, 'category' => 'Dining'],
            ['name' => 'Airport Transfer', 'slug' => 'airport-transfer', 'description' => 'One-way airport transfer', 'price' => 75000, 'category' => 'Transport'],
            ['name' => 'Conference Hall', 'slug' => 'conference-hall', 'description' => 'Conference room rental per hour', 'price' => 125000, 'category' => 'Business'],
            ['name' => 'Spa Treatment', 'slug' => 'spa', 'description' => 'Full body massage and spa', 'price' => 100000, 'category' => 'Wellness'],
            ['name' => 'Room Service', 'slug' => 'room-service', 'description' => '24/7 in-room dining service', 'price' => 12500, 'category' => 'Dining'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $defaultSettings = [
            ['key' => 'hotel_name', 'value' => 'BungeStay'],
            ['key' => 'currency', 'value' => 'TZS'],
            ['key' => 'tax_rate', 'value' => '18'],
            ['key' => 'checkin_time', 'value' => '14:00'],
            ['key' => 'checkout_time', 'value' => '11:00'],
            ['key' => 'booking_auto_confirm', 'value' => '0'],
            ['key' => 'enable_online_payments', 'value' => '1'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::create($setting);
        }
    }
}
