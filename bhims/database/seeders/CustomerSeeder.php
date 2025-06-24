<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
                'gst_number' => 'GST123456',
                'notes' => 'Regular customer',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '9876543210',
                'address' => '456 Oak Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90001',
                'country' => 'USA',
                'gst_number' => 'GST789012',
                'notes' => 'Wholesale customer',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '5551234567',
                'address' => '789 Pine St',
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60007',
                'country' => 'USA',
                'gst_number' => 'GST345678',
                'notes' => 'New customer',
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'phone' => '5559876543',
                'address' => '321 Elm St',
                'city' => 'Houston',
                'state' => 'TX',
                'postal_code' => '77001',
                'country' => 'USA',
                'gst_number' => 'GST901234',
                'notes' => 'VIP customer',
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie@example.com',
                'phone' => '5554567890',
                'address' => '654 Maple Dr',
                'city' => 'Phoenix',
                'state' => 'AZ',
                'postal_code' => '85001',
                'country' => 'USA',
                'gst_number' => 'GST567890',
                'notes' => 'Corporate account',
            ],
        ];


        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
