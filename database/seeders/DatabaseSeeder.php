<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use App\Models\Attraction;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Page;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->admin()->create([
            'name' => 'System Admin',
        ]);

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
        Page::create([
            'slug'    => 'about-bantayan-island',
            'title'   => 'About Bantayan Island',
            'content' => '<p>Bantayan Island is a paradise located in the northern part of Cebu...</p>',
        ]);

        Page::create([
            'slug'    => 'how-to-get-there',
            'title'   => 'How to Get There',
            'content' => '<p>From Cebu City, take a bus to Hagnaya Port, then a ferry to Bantayan Island...</p>',
        ]);
    }
}
