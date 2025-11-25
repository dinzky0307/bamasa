<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use App\Models\Attraction;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // System Admin (main)
User::updateOrCreate(
    ['email' => 'admin@bantayan.test'],
    [
        'name'     => 'System Admin',
        'password' => Hash::make('admin123'),
        'role'     => 'admin',
        
    ]
);

// LGU Bantayan Admin
User::updateOrCreate(
    ['email' => 'bantayan@gmail.com'],
    [
        'name'         => 'LGU Bantayan Admin',
        'password'     => Hash::make('admin123'),
        'role'         => 'admin',
        'municipality' => 'Bantayan',
        'lgu_logo'     => 'logos/bantayan.png',
    ]
);

// LGU Madridejos Admin
User::updateOrCreate(
    ['email' => 'madridejos@gmail.com'],
    [
        'name'         => 'LGU Madridejos Admin',
        'password'     => Hash::make('admin123'),
        'role'         => 'admin',
        'municipality' => 'Madridejos',
        'lgu_logo'     => 'logos/madridejos.png',
    ]
);

// LGU Santa Fe Admin
User::updateOrCreate(
    ['email' => 'santafe@gmail.com'],
    [
        'name'         => 'LGU Santa Fe Admin',
        'password'     => Hash::make('admin123'),
        'role'         => 'admin',
        'municipality' => 'Santa Fe',
        'lgu_logo'     => 'logos/santafe.png', // optional placeholder
    ]
);

        
      


        // Some tourists
        $tourists = User::factory(10)->create([
            'role' => 'tourist',
        ]);

        // Businesses
        $businesses = Business::factory(5)->create();

        // Attractions (optional, if you have AttractionFactory)
        if (class_exists(Attraction::class)) {
            Attraction::factory(8)->create();
        }

        // Simple bookings + reviews (optional, if you created those factories)
        if (class_exists(Booking::class) && class_exists(Review::class)) {
            foreach ($businesses as $business) {
                Booking::factory(3)->create([
                    'business_id' => $business->id,
                    'user_id'     => $tourists->random()->id,
                ]);

                Review::factory(2)->create([
                    'business_id' => $business->id,
                    'user_id'     => $tourists->random()->id,
                ]);
            }
        }

        // Static pages (About, How to Get There)
Page::updateOrCreate(
    ['slug' => 'about-bantayan-island'], // lookup by unique slug
    [
        'title'   => 'About Bantayan Island',
        'content' => '<p>Bantayan Island is a paradise located in the northern part of Cebu...</p>',
    ]
);

Page::updateOrCreate(
    ['slug' => 'how-to-get-there'],
    [
        'title'   => 'How to Get There',
        'content' => '<p>From Cebu City, take a bus to Hagnaya Port, then a ferry to Bantayan Island...</p>',
    ]
);

    }
}
