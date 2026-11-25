<?php

namespace App\Services;
use App\Email\DynamicMail;
use App\Models\EmailTemplate;
use App\Utils\TemplateParser;
use Mail;

class EmailService
{
    /**
     * Send email using template slug.
     */
    public static function send(
        string $slug,
        string|array $to,
        array $variables = []
    ): bool {

        $template = EmailTemplate::where('slug', $slug)
            ->where('status', true)
            ->first();
      
        if (!$template) {
            return false;
        }

        $subject = TemplateParser::parse(
            $template->subject,
            $variables
        );

        $body = TemplateParser::parse(
            $template->body,
            $variables
        );

        Mail::to($to)
            ->send(new DynamicMail($subject, $body));

        return true;
    }

    /**
     * Queue Email
     */
    public static function queue(
        string $slug,
        string|array $to,
        array $variables = []
    ): bool {

        $template = EmailTemplate::where('slug', $slug)
            ->where('status', true)
            ->first();

        if (!$template) {
            return false;
        }

        $subject = TemplateParser::parse(
            $template->subject,
            $variables
        );

        $body = TemplateParser::parse(
            $template->body,
            $variables
        );

        Mail::to($to)
            ->queue(new DynamicMail($subject, $body));

        return true;
    }
}