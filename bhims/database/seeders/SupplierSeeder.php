<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Premium Flour Mills',
                'contact_person' => 'John Smith',
                'email' => 'john@premiumflour.com',
                'phone' => '555-0101',
                'address' => '123 Mill Street, Flour City, FC 12345',
                'tax_number' => 'TAX-12345678',
                'notes' => 'Primary flour supplier, reliable delivery',
            ],
            [
                'name' => 'Golden Grains Co.',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@goldengrains.com',
                'phone' => '555-0102',
                'address' => '456 Grain Avenue, Harvest Town, HT 23456',
                'tax_number' => 'TAX-23456789',
                'notes' => 'Specializes in organic and specialty grains',
            ],
            [
                'name' => 'Dairy Delight',
                'contact_person' => 'Mike Brown',
                'email' => 'mike@dairydelight.com',
                'phone' => '555-0103',
                'address' => '789 Pasture Lane, Milk County, MC 34567',
                'tax_number' => 'TAX-34567890',
                'notes' => 'Local dairy supplier, next day delivery',
            ],
            [
                'name' => 'Sweetness Inc.',
                'contact_person' => 'Emily Davis',
                'email' => 'emily@sweetness.com',
                'phone' => '555-0104',
                'address' => '321 Sugar Road, Sweetville, SV 45678',
                'tax_number' => 'TAX-45678901',
                'notes' => 'Specialty sugars and sweeteners',
            ],
            [
                'name' => 'Baking Essentials',
                'contact_person' => 'David Wilson',
                'email' => 'david@bakingessentials.com',
                'phone' => '555-0105',
                'address' => '654 Mixer Drive, Batterton, BT 56789',
                'tax_number' => 'TAX-56789012',
                'notes' => 'One-stop shop for all baking needs',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}
