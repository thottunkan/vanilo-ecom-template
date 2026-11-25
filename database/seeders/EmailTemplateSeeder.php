<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Test Email',
                'subject' => 'This is a test email',
            ],
            [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to {{$app_name}}',
            ],
            [
                'name' => 'User Registration',
                'subject' => 'Welcome to {{$app_name}}',
            ],
            [
                'name' => 'Email Verification',
                'subject' => 'Verify Your Email Address',
            ],
            [
                'name' => 'Verify OTP',
                'subject' => 'Your Verification Code',
            ],
            [
                'name' => 'Forgot Password',
                'subject' => 'Password Reset Request',
            ],
            [
                'name' => 'Password Reset',
                'subject' => 'Reset Your Password',
            ],
            [
                'name' => 'Password Reset Success',
                'subject' => 'Your Password Has Been Reset',
            ],
            [
                'name' => 'Password Changed',
                'subject' => 'Your Password Has Been Changed',
            ],
            [
                'name' => 'Order Confirmation',
                'subject' => 'Your Order #{{$order_number}} Has Been Received',
            ],
            [
                'name' => 'Order Confirmed',
                'subject' => 'Your Order #{{$order_number}} Has Been Confirmed',
            ],
            [
                'name' => 'Order Placed',
                'subject' => 'Your Order #{{$order_number}} Has Been Placed',
            ],
            [
                'name' => 'Order Shipped',
                'subject' => 'Your Order #{{$order_number}} Has Been Shipped',
            ],
            [
                'name' => 'Order Delivered',
                'subject' => 'Your Order #{{$order_number}} Has Been Delivered',
            ],
            [
                'name' => 'Order Return Requested',
                'subject' => 'Return Request Received for Order #{{$order_number}}',
            ],
            [
                'name' => 'Order Cancelled',
                'subject' => 'Your Order #{{$order_number}} Has Been Cancelled',
            ],
            [
                'name' => 'Newsletter Email',
                'subject' => 'Latest News from {{$app_name}}',
            ],
            [
                'name' => 'Newsletter Subscription',
                'subject' => 'Thanks for Subscribing',
            ],
            [
                'name' => 'Newsletter Unsubscription',
                'subject' => 'You Have Been Unsubscribed',
            ],
            [
                'name' => 'Contact Message',
                'subject' => "We've Received Your Message",
            ],
            [
                'name' => 'Contact Confirmation',
                'subject' => 'We Have Received Your Inquiry',
            ],
            [
                'name' => 'Contact Reply',
                'subject' => 'Response to Your Inquiry',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => Str::slug($template['name'])],
                [
                    'name' => $template['name'],
                    'subject' => $template['subject'],
                    'body' => '',
                ]
            );
        }
        $this->command->info('Email template Seeding completed!.');
    }
}
