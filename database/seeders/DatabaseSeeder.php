<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Lunar\Models\Customer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Run Core Store Data Seeder
        $this->call(StoreDataSeeder::class);

        // 2. Create sample customer user
        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Aarav Sharma',
                'password' => Hash::make('password'),
            ]
        );

        // Link with Lunar Customer
        $customer = Customer::firstOrCreate(
            ['first_name' => 'Aarav', 'last_name' => 'Sharma'],
            [
                'title' => 'Mr',
                'meta' => ['phone' => '+44 7700 900077'],
            ]
        );

        $customer->users()->syncWithoutDetaching([$user->id]);
    }
}
