<?php

namespace App\Utils;
class TemplateParser
{
    // public static function parse(?string $content, array $data = []): string
    // {
    //     if (!$content) {
    //         return '';
    //     }

    //     foreach ($data as $key => $value) {
    //         $content = str_replace(
    //             '{{' . $key . '}}',
    //             (string) $value,
    //             $content
    //         );
    //     }

    //     // Remove unreplaced placeholders
    //     $content = preg_replace('/{{(.*?)}}/', '', $content);

    //     return $content;
    // }

    public static function parse(?string $content, array $data = []): string
    {
        if (!$content) {
            return '';
        }

        // Matches {{ key }}, {{key}}, {{ $key }}, {{$key}}
        $content = preg_replace_callback(
            '/\{\{\s*\$?([a-zA-Z0-9_]+)\s*\}\}/',
            function ($matches) use ($data) {
                $key = $matches[1];
                return array_key_exists($key, $data)
                    ? (string) $data[$key]
                    : ''; // unknown placeholder -> blank
            },
            $content
        );

        return $content;
    }
    
    // public static function variables(): array
    // {
    //     return [
    //         '{{customer_name}}',
    //         '{{website_name}}',
    //         '{{verification_url}}',
    //         '{{reset_url}}',
    //         '{{order_id}}',
    //         '{{order_date}}',
    //         '{{invoice_url}}',
    //         '{{tracking_url}}',
    //         '{{support_email}}',
    //         '{{payment_method}}',
    //         '{{amount}}',
    //         '{{refund_amount}}',
    //     ];
    // }



}

// Usage

// $data = [

//     'customer_name'=>$user->name,
//     'website_name'=>config('app.name'),
//     'verification_url'=>$url,
// ];

// Then
// $body = TemplateParser::parse(
//     $template->body,
//     $data
// );

// $subject = TemplateParser::parse(
//     $template->subject,
//     $data
// );
// $

