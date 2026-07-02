<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(Auth::class, function () {
            $factory = new Factory;
            $credentials = env('FIREBASE_CREDENTIALS');

            if ($credentials) {
                $factory = $factory->withServiceAccount($credentials);
            } else if (app()->environment('local')) {
                // Em ambiente local offline, injeta um mock para evitar erro ao construir a classe
                $dummyCredentials = [
                    "type" => "service_account",
                    "project_id" => "mock-project",
                    "private_key_id" => "mock-key",
                    "private_key" => "-----BEGIN PRIVATE KEY-----\nMOCK\n-----END PRIVATE KEY-----\n",
                    "client_email" => "mock@mock-project.iam.gserviceaccount.com",
                    "client_id" => "12345",
                    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                    "token_uri" => "https://oauth2.googleapis.com/token",
                    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                    "client_x509_cert_url" => "https://www.googleapis.com/role/mock"
                ];
                $factory = $factory->withServiceAccount($dummyCredentials);
            } else {
                $factory = $factory->withServiceAccount(config('firebase.credentials'));
            }

            return $factory->createAuth();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
