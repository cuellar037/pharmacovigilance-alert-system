<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use App\Models\Medication;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        // User for testing purposes
        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@pharmacovigilance.test', 
            'password' => bcrypt('123456'),
        ]);

        // Create a specific medication for testing purposes
        $targetMedication = Medication::create([
            'name' => 'Ibuprofeno 400mg',
            'lot_number' => '951357',
        ]);

        // Create additional medications for testing purposes
        $otherMedications = Medication::factory()->count(5)->create();

        // Create customers for testing purposes
        $customers = Customer::factory()->count(15)->create();

        // --- Positive scenario: orders with batch 951357 within the last month ---
        foreach($customers->random(6) as $customer){
            $order = Order::create([
                'customer_id' => $customer->id,
                'purchase_date' => now()->subDays(rand(1, 29)),
            ]);

            OrderItem::create([
                'order_id' => $order->id, 
                'medication_id' => $targetMedication->id,
            ]); 
        }

        // --- Control scenario: same batch but OUTSIDE the range (more than 30 days) ---
        foreach ($customers->random(3) as $customer) {
            $order = Order::create([
                'customer_id' => $customer->id,
                'purchase_date' => now()->subDays(rand(60, 120)),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'medication_id' => $targetMedication->id,
            ]);
        }

        // Control scenario: other lot numbers, random dates

        Order::factory()
            ->count(10)
            ->create([
                'customer_id' => fn () => $customers->random()->id,
            ])
            ->each( function (Order $order) use ($otherMedications){
                OrderItem::create([
                    'order_id'=> $order->id, 
                    'medication_id' => $otherMedications->random()->id,
                ]);
            });
    }
}
