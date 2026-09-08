<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Role::create(['name' => 'Owner']);
        $cashier = Role::create(['name' => 'Cashier']);
        Role::create(['name' => 'Customer']);

        User::create([
            'full_name' => 'Shop Owner',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role_id' => $owner->id,
            'status' => 'active',
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);

        User::create([
            'full_name' => 'Front Desk Cashier',
            'username' => 'cashier',
            'email' => 'cashier@example.com',
            'password' => Hash::make('admin'),
            'role_id' => $cashier->id,
            'status' => 'active',
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);

        $settings = [
            'shop_name' => 'ShopZone Kenya',
            'shop_contact' => 'Nairobi, Kenya',
            'shop_phone' => '0700 000 000',
            'shop_email' => 'info@shopzone.example',
            'shop_address' => 'Tom Mboya Street, Nairobi',
            'currency' => 'KES',
            'low_stock_alert' => '5',
        ];
        foreach ($settings as $k => $v) {
            DB::table('settings')->insert(['setting_key' => $k, 'setting_value' => $v, 'updated_at' => now()]);
        }

        $cats = [
            "Men's Shoes", "Women's Shoes", "Children's Shoes", "Sports Shoes", "School Shoes",
            'Sandals', 'Boots', "Men's Clothing", "Women's Clothing", "Children's Clothing",
            'T-Shirts', 'Shirts', 'Trousers', 'Dresses', 'Jackets', 'Shorts',
        ];
        foreach ($cats as $name) {
            Category::create(['name' => $name, 'status' => 'active']);
        }

        $s1 = Supplier::create(['name' => 'Nairobi Footwear Distributors', 'phone' => '0700111222', 'status' => 'active']);
        $s2 = Supplier::create(['name' => 'Coast Apparel Ltd', 'phone' => '0711222333', 'status' => 'active']);
        $s3 = Supplier::create(['name' => 'Rift Valley Traders', 'phone' => '0722333444', 'status' => 'active']);

        $products = [
            ['Classic Leather Oxford', 'SH-OXF-001', "Men's Shoes", $s1->id, 'Shoes', 'Bata', 'Men', '42', 'Black', 2200, 3500, 25],
            ["Women's Running Sneaker", 'SH-RUN-002', 'Sports Shoes', $s1->id, 'Shoes', 'Nike', 'Women', '38', 'White', 2800, 4500, 18],
            ['Kids School Shoe', 'SH-SCH-003', 'School Shoes', $s3->id, 'Shoes', 'Power', 'Unisex', '32', 'Black', 900, 1500, 40],
            ['Canvas Sneaker', 'SH-CNV-004', 'Sports Shoes', $s1->id, 'Shoes', 'Converse', 'Unisex', '40', 'Red', 1200, 2200, 30],
            ['Leather Ankle Boot', 'SH-BOT-005', 'Boots', $s1->id, 'Shoes', 'Clarks', 'Men', '43', 'Brown', 3200, 5200, 12],
            ["Women's Flat Sandal", 'SH-SAN-006', 'Sandals', $s2->id, 'Shoes', 'Bata', 'Women', '37', 'Beige', 700, 1300, 22],
            ['Cotton Crew T-Shirt', 'CL-TSH-001', 'T-Shirts', $s2->id, 'Clothing', 'Generic', 'Unisex', 'L', 'Navy', 350, 800, 50],
            ['Slim Fit Chino', 'CL-TRS-002', 'Trousers', $s2->id, 'Clothing', 'Wrangler', 'Men', '32', 'Khaki', 1100, 2200, 20],
            ['Summer Floral Dress', 'CL-DRS-003', 'Dresses', $s2->id, 'Clothing', 'Local', 'Women', 'M', 'Floral', 1500, 2800, 15],
            ['Kids Hoodie', 'CL-JCK-004', 'Jackets', $s3->id, 'Clothing', 'Generic', 'Children', '10Y', 'Grey', 900, 1700, 16],
            ["Men's Formal Shirt", 'CL-SHT-005', 'Shirts', $s2->id, 'Clothing', 'Arrow', 'Men', 'L', 'White', 800, 1600, 28],
            ['Sports Shorts', 'CL-SHT-006', 'Shorts', $s3->id, 'Clothing', 'Puma', 'Unisex', 'M', 'Black', 500, 1100, 35],
        ];

        foreach ($products as $p) {
            $cat = Category::where('name', $p[2])->first();
            Product::create([
                'product_name' => $p[0],
                'sku' => $p[1],
                'category_id' => $cat?->id,
                'supplier_id' => $p[3],
                'department' => $p[4],
                'brand' => $p[5],
                'gender' => $p[6],
                'size' => $p[7],
                'color' => $p[8],
                'buying_price' => $p[9],
                'selling_price' => $p[10],
                'quantity' => $p[11],
                'reorder_level' => 5,
                'status' => 'active',
            ]);
        }

        $zones = [
            ['Nairobi CBD', 150], ['Westlands', 200], ['Eastlands', 180], ['Karen', 350], ['Thika Road', 250],
        ];
        foreach ($zones as $z) {
            DB::table('delivery_zones')->insert([
                'destination' => $z[0], 'charge' => $z[1], 'active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
