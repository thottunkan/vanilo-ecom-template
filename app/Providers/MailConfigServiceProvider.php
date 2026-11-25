<?php

namespace App\Providers;

use App\Models\EmailSetting;
use Illuminate\Support\ServiceProvider;
use Schema;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        if (!Schema::hasTable('email_settings')) {
            return;
        }

        $setting = EmailSetting::first();

        if (!$setting || !$setting->is_active) {
            return;
        }

        config([
            'mail.default' => $setting->mail_mailer,
            'mail.mailers.smtp.host' => $setting->mail_host,
            'mail.mailers.smtp.port' => $setting->mail_port,
            'mail.mailers.smtp.username' => $setting->mail_username,
            'mail.mailers.smtp.password' => $setting->mail_password,
            'mail.mailers.smtp.encryption' => $setting->mail_encryption,
            'mail.from.address' => $setting->mail_from_address,
            'mail.from.name' => $setting->mail_from_name,
        ]);
    }
}
