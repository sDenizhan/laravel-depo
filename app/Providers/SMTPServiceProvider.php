<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SMTPServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        $smtp = Setting::getGroup('email');

        if ( $smtp ) {

            $config = [
                'driver' => 'smtp',
                'host' => $smtp['smtp_host'],
                'port' => $smtp['smtp_port'],
                'username' => $smtp['smtp_username'],
                'password' => $smtp['smtp_password'],
                'encryption' => $smtp['smtp_encryption'],
                'from' => [
                    'address' => $smtp['smtp_from_email'],
                    'name' => $smtp['smtp_from_name'],
                ],
            ];

            Config::set('mail', $config);
        }
    }
}
