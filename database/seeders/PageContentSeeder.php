<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'page_name' => 'Home',
                'url' => 'home',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Shop',
                'url' => 'shop',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'About Us',
                'url' => 'about-us',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Contact Us',
                'url' => 'contact-us',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Blog',
                'url' => 'blog',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'FAQ',
                'url' => 'faq',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => '404 Not Found',
                'url' => 'not-found',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Empty Cart',
                'url' => 'empty-cart',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Empty Wishlist',
                'url' => 'empty-wishlist',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Empty Orders',
                'url' => 'empty-orders',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'Order Complete',
                'url' => 'order-complete',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'sign-in',
                'url' => 'sign-in',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'sign-up',
                'url' => 'sign-up',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'forgot-password',
                'url' => 'forgot-password',
                'page_contents' => null,
                'status' => 'inactive'
            ],
            [
                'page_name' => 'reset-password',
                'url' => 'reset-password',
                'page_contents' => null,
                'status' => 'inactive'
            ],
        ];

        foreach ($pages as $page) {
            DB::table('page_contents')->updateOrInsert(
                ['url' => $page['url']],
                [
                    'page_name' => $page['page_name'],
                    'page_contents' => json_encode(['status' => 'inactive'], true),
                    'status' => $page['status'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        $this->command->info('Page Content Seeding Completed!');
    }
}
