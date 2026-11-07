<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123'),
            ]
        );

        // 1. Categories (6 items)
        $categories = [
            ['nama' => 'Elektronik & Gadget'],
            ['nama' => 'Pakaian & Fashion'],
            ['nama' => 'Makanan & Minuman'],
            ['nama' => 'Perlengkapan Rumah'],
            ['nama' => 'Kesehatan & Kecantikan'],
            ['nama' => 'Olahraga & Outdoor'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['nama' => $cat['nama']]);
        }

        // 2. Products (6 items)
        $products = [
            [
                'nama' => 'Laptop Asus Vivobook 14',
                'slug' => Str::slug('Laptop Asus Vivobook 14'),
                'description' => 'Laptop kerja ringan dengan prosesor Intel Core i5, RAM 8GB, SSD 512GB.',
                'price' => 8500000.00,
                'stock' => 15,
            ],
            [
                'nama' => 'Smartphone Samsung Galaxy A54',
                'slug' => Str::slug('Smartphone Samsung Galaxy A54'),
                'description' => 'Layar Super AMOLED 120Hz, kamera jernih 50MP OIS, baterai 5000mAh.',
                'price' => 5999000.00,
                'stock' => 20,
            ],
            [
                'nama' => 'Kemeja Katun Pria Formal',
                'slug' => Str::slug('Kemeja Katun Pria Formal'),
                'description' => 'Bahan katun premium adem dan nyaman digunakan seharian di kantor.',
                'price' => 175000.00,
                'stock' => 50,
            ],
            [
                'nama' => 'Kopi Arabika Gayo 250gr',
                'slug' => Str::slug('Kopi Arabika Gayo 250gr'),
                'description' => 'Biji kopi sangrai pilihan aroma wangi asam segar khas dataran tinggi Gayo.',
                'price' => 65000.00,
                'stock' => 80,
            ],
            [
                'nama' => 'Air Fryer Digital 4L',
                'slug' => Str::slug('Air Fryer Digital 4L'),
                'description' => 'Penggoreng tanpa minyak multifungsi hemat daya listrik dan mudah dibersihkan.',
                'price' => 450000.00,
                'stock' => 25,
            ],
            [
                'nama' => 'Sepatu Lari Ultralight Running',
                'slug' => Str::slug('Sepatu Lari Ultralight Running'),
                'description' => 'Sol empuk meredam benturan dengan sirkulasi udara baik untuk lari jarak jauh.',
                'price' => 320000.00,
                'stock' => 30,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }

        // 3. Branches (Jakarta & Indramayu - 6 items)
        $branches = [
            // Jakarta Branches
            [
                'nama' => 'Cabang Jakarta Pusat - Monas',
                'alamat' => 'Jl. Medan Merdeka Barat No. 12, Gambir, Jakarta Pusat',
                'telepon' => '021-3456789',
                'latitude' => -6.1753924,
                'longitude' => 106.8271528,
            ],
            [
                'nama' => 'Cabang Jakarta Selatan - Blok M',
                'alamat' => 'Jl. Melawai Raya No. 45, Kebayoran Baru, Jakarta Selatan',
                'telepon' => '021-7234567',
                'latitude' => -6.2443190,
                'longitude' => 106.7978860,
            ],
            [
                'nama' => 'Cabang Jakarta Utara - Kelapa Gading',
                'alamat' => 'Jl. Boulevard Raya Blok LA-6 No. 1, Kelapa Gading, Jakarta Utara',
                'telepon' => '021-4587123',
                'latitude' => -6.1581450,
                'longitude' => 106.9099450,
            ],
            // Indramayu Branches
            [
                'nama' => 'Cabang Indramayu Kota - Alun-Alun',
                'alamat' => 'Jl. Mayjen Sutoyo No. 8, Lemahabang, Kec. Indramayu, Kabupaten Indramayu',
                'telepon' => '0234-271234',
                'latitude' => -6.3268880,
                'longitude' => 108.3223010,
            ],
            [
                'nama' => 'Cabang Indramayu - Jatibarang',
                'alamat' => 'Jl. Mayor Dasuki No. 88, Jatibarang, Kabupaten Indramayu',
                'telepon' => '0234-351890',
                'latitude' => -6.4745810,
                'longitude' => 108.3142750,
            ],
            [
                'nama' => 'Cabang Indramayu - Karangampel',
                'alamat' => 'Jl. Raya Karangampel Barat No. 23, Karangampel, Kabupaten Indramayu',
                'telepon' => '0234-482110',
                'latitude' => -6.4632000,
                'longitude' => 108.4526000,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['nama' => $branch['nama']], $branch);
        }
    }
}
