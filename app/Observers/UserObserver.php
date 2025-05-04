<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\WelcomeEmail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            $user->notify(new WelcomeEmail());
            \Log::info("Email de boas-vindas disparado para: {$user->email}");
        } catch (\Exception $e) {
            \Log::error("Erro ao enviar email: {$e->getMessage()}");
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
