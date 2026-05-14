<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Fabric;
use App\Models\InventoryLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin (Biar kamu ga perlu register ulang kalau di-reset)
        $user = User::firstOrCreate([
            'name' => 'Admin Fabric',
            'email' => 'admin@gmail.com', // Email untuk login
            'password' => bcrypt('password'),
            'phone' => '082137943030',
            'role' => 'admin', // Pastikan kolom role ada
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com', // Email untuk login
            'password' => bcrypt('password'),
            'phone' => '082137943030',
            'role' => 'user', // Pastikan kolom role ada
        ]);

        // 2. Buat Data Kategori
        $catCotton = Category::create(['name' => 'Cotton']);
        $catSilk = Category::create(['name' => 'Silk']);
        $catWool = Category::create(['name' => 'Wool']);

        // 3. Buat Data Supplier
        $supA = Supplier::create(['name' => 'PT. Tekstil Maju']);
        $supB = Supplier::create(['name' => 'CV. Sutra Abadi']);

        // 4. Buat Data Kain (Fabrics)
        $fabric1 = Fabric::create([
            'name' => 'Premium Cotton Combed 30s',
            'category_id' => $catCotton->id,
            'supplier_id' => $supA->id,
            'color' => 'Navy Blue',
            'material' => '100% Cotton',
            'price_per_meter' => 45000,
            'stock_meter' => 100,
            'description' => 'Bahan kaos adem dan menyerap keringat.'
        ]);

        $fabric2 = Fabric::create([
            'name' => 'Sutra Halus Import',
            'category_id' => $catSilk->id,
            'supplier_id' => $supB->id,
            'color' => 'Red Maroon',
            'material' => 'Pure Silk',
            'price_per_meter' => 125000,
            'stock_meter' => 50,
            'description' => 'Sutra premium untuk gaun pesta.'
        ]);

        // 5. Catat Log Inventory (Supaya data stok sinkron)
        InventoryLog::create([
            'fabric_id' => $fabric1->id,
            'user_id' => $user->id,
            'change_type' => 'initial',
            'change_amount' => 100,
            'note' => 'Seeding data awal'
        ]);

        InventoryLog::create([
            'fabric_id' => $fabric2->id,
            'user_id' => $user->id,
            'change_type' => 'initial',
            'change_amount' => 50,
            'note' => 'Seeding data awal'
        ]);
    }
}