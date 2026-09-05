<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::query()->updateOrCreate(
            ['email' => 'supplier-one@example.com'],
            [
                'name' => 'supplier-a',
                'phone' => '+10000000001',
                'telegram' => '@supplier_one',
                'whatsapp' => '+10000000001',
                'payment_details' => 'Bank transfer details for Supplier One',
                'status' => 'active',
                'comment' => 'First test supplier',
            ]
        );

        Supplier::query()->updateOrCreate(
            ['email' => 'supplier-two@example.com'],
            [
                'name' => 'supplier-b',
                'phone' => '+10000000002',
                'telegram' => '@supplier_two',
                'whatsapp' => '+10000000002',
                'payment_details' => 'Bank transfer details for Supplier Two',
                'status' => 'active',
                'comment' => 'Second test supplier',
            ]
        );
    }
}
