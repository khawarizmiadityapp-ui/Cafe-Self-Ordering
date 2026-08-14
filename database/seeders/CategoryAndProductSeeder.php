<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryAndProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kopi',
                'slug' => 'kopi',
                'description' => 'Olahan biji kopi pilihan dengan cita rasa khas cafe',
                'sort_order' => 1,
                'products' => [
                    [
                        'name' => 'Es Kopi Susu Aren',
                        'price' => 15000,
                        'description' => 'Espresso dengan susu segar dan gula aren murni',
                        'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Americano Cold',
                        'price' => 18000,
                        'description' => 'Double shot espresso disajikan dingin menyegarkan',
                        'image' => 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Caramel Macchiato',
                        'price' => 24000,
                        'description' => 'Espresso, susu steamed, sirup vanila dan siram sirup karamel',
                        'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Cappuccino Hot',
                        'price' => 20000,
                        'description' => 'Espresso dengan foam susu tebal dan taburan coklat',
                        'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                ]
            ],
            [
                'name' => 'Non-Kopi',
                'slug' => 'non-kopi',
                'description' => 'Minuman segar non-kafein untuk menemani waktu santaimu',
                'sort_order' => 2,
                'products' => [
                    [
                        'name' => 'Matcha Cream Latte',
                        'price' => 22000,
                        'description' => 'Bubuk matcha jepang asli dicampur susu creamy',
                        'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Chocolate Artisan',
                        'price' => 22000,
                        'description' => 'Coklat pekat kualitas tinggi dengan sentuhan manis pas',
                        'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Peach Sparkling Tea',
                        'price' => 19000,
                        'description' => 'Teh persik dingin berkarbonasi dengan rasa segar berbuah',
                        'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                ]
            ],
            [
                'name' => 'Makanan',
                'slug' => 'makanan',
                'description' => 'Sajian makanan lezat dan mengenyangkan',
                'sort_order' => 3,
                'products' => [
                    [
                        'name' => 'Nasi Goreng Special Cafe',
                        'price' => 28000,
                        'description' => 'Nasi goreng bumbu rempah khas dilengkapi telur ciplok dan kerupuk',
                        'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Spaghetti Carbonara',
                        'price' => 32000,
                        'description' => 'Pasta spaghetti saus krim keju gurih dengan potongan smoked beef',
                        'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Chicken Katsu Don',
                        'price' => 30000,
                        'description' => 'Ayam katsu renyah dengan saus teriyaki dan telur di atas nasi hangat',
                        'image' => 'https://images.unsplash.com/photo-1569058242253-92a9c755a0ec?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                ]
            ],
            [
                'name' => 'Snack',
                'slug' => 'snack',
                'description' => 'Cemilan gurih dan manis cocok untuk ngobrol',
                'sort_order' => 4,
                'products' => [
                    [
                        'name' => 'Kentang Goreng Truffle',
                        'price' => 20000,
                        'description' => 'French fries renyah dengan minyak truffle dan keju parut',
                        'image' => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Roti Bakar Coklat Keju',
                        'price' => 18000,
                        'description' => 'Roti bakar empuk isi coklat meises melimpah dan taburan keju cheddar',
                        'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&auto=format&fit=crop&q=80',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Cireng Bumbu Rujak',
                        'price' => 15000,
                        'description' => 'Cireng krispi kenyal disajikan dengan bumbu rujak pedas manis',
                        'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=600&auto=format&fit=crop&q=80',
                        'is_available' => false, // Set 1 item unavailable to test out-of-stock badge!
                    ],
                ]
            ],
        ];

        foreach ($categories as $catData) {
            $prods = $catData['products'];
            unset($catData['products']);

            $cat = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );

            foreach ($prods as $p) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($p['name'])],
                    array_merge($p, ['category_id' => $cat->id, 'slug' => Str::slug($p['name'])])
                );
            }
        }
    }
}
