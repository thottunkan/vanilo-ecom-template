<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        Policy::truncate();

        $policies = [
            [
                'title' => 'Privacy Policy',
                'content' => <<<TEXT
We value your privacy and are committed to protecting your personal information.

Information We Collect:
- Name, email address, phone number, and shipping address.
- Payment information is processed securely by our payment providers.
- Device and browsing information to improve our services.

How We Use Your Information:
- Process and deliver your orders.
- Communicate order updates.
- Improve our website and customer experience.
- Send promotional emails with your consent.

We do not sell your personal information to third parties.

You may contact us at any time to request access, correction, or deletion of your personal data.
TEXT,
                'order' => 1,
            ],

            [
                'title' => 'Terms & Conditions',
                'content' => <<<TEXT
By using our website, you agree to these Terms and Conditions.

- All prices are subject to change without notice.
- Orders are subject to product availability.
- We reserve the right to cancel fraudulent or suspicious orders.
- Product images are for illustration purposes only.
- Customers are responsible for providing accurate shipping information.
- Misuse of the website may result in account suspension.

These terms are governed by applicable local laws.
TEXT,
                'order' => 2,
            ],

            [
                'title' => 'Shipping Policy',
                'content' => <<<TEXT
Orders are processed within 1-3 business days.

Estimated Delivery:
- Local: 2-5 business days.
- International: 7-21 business days.

Shipping charges are calculated during checkout.

Customers will receive a tracking number once the order has been dispatched.

Delivery times may vary due to customs, weather, or courier delays.
TEXT,
                'order' => 3,
            ],

            [
                'title' => 'Return & Refund Policy',
                'content' => <<<TEXT
Returns are accepted within 14 days of delivery.

Items must:
- Be unused.
- Be in original packaging.
- Include all accessories and receipts.

Refunds are issued after inspection of returned items.

Shipping charges are generally non-refundable unless the item is defective or the wrong product was shipped.
TEXT,
                'order' => 4,
            ],

            [
                'title' => 'Payment Policy',
                'content' => <<<TEXT
We accept major credit cards, debit cards, bank transfers, and approved digital wallets.

All online payments are encrypted and processed through secure payment gateways.

Orders are confirmed only after successful payment authorization.

We reserve the right to cancel unpaid orders.
TEXT,
                'order' => 5,
            ],

            [
                'title' => 'Cancellation Policy',
                'content' => <<<TEXT
Orders may be cancelled before they are shipped.

Once an order has been dispatched, it cannot be cancelled and must follow the Return & Refund Policy.

If payment has already been processed, eligible refunds will be issued to the original payment method.
TEXT,
                'order' => 6,
            ],

            [
                'title' => 'Cookie Policy',
                'content' => <<<TEXT
Our website uses cookies to:

- Remember your preferences.
- Keep you logged in.
- Analyze website traffic.
- Improve user experience.

You can disable cookies through your browser settings, although some features may not function correctly.
TEXT,
                'order' => 7,
            ],

            [
                'title' => 'Warranty Policy',
                'content' => <<<TEXT
Warranty coverage varies by product.

If a product includes a manufacturer's warranty, customers should retain proof of purchase.

Damage caused by misuse, accidents, or unauthorized repairs is not covered.
TEXT,
                'order' => 8,
            ],

            [
                'title' => 'Contact & Support',
                'content' => <<<TEXT
Need assistance?

Our customer support team is available during business hours.

Please contact us through our website, email, or telephone for inquiries regarding orders, returns, or products.
TEXT,
                'order' => 9,
            ],
        ];

        foreach ($policies as $policy) {
            Policy::create([
                'title' => $policy['title'],
                'content' => $policy['content'],
                'order' => $policy['order'],
                'slug' => Str::slug($policy['title']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Policies seeding completed!.');
    }
}