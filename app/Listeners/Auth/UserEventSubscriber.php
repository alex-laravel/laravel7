<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * @param Attempting $event
     */
    public function onUserAttempting(Attempting $event)
    {
        \Log::debug('User Attempting: ' . $event->credentials['email']);
    }

    /**
     * @param Authenticated $event
     */
    public function onUserAuthenticated(Authenticated $event)
    {
        \Log::debug('User Authenticated: ' . $event->user->email);
    }

    /**
     * @param Failed $event
     */
    public function onUserFailed(Failed $event)
    {
        \Log::debug('User Failed: ' . $event->credentials['email']);
    }

    /**
     * @param Lockout $event
     */
    public function onUserLockout(Lockout $event)
    {
        \Log::debug('User Lockout: ' . $event->request->email);
    }

    /**
     * @param Login $event
     */
    public function onUserLogin(Login $event)
    {
        \Log::debug('User Login: ' . $event->user->email);
    }

    /**
     * @param Logout $event
     */
    public function onUserLogout(Logout $event)
    {
        \Log::debug('User Logout: ' . $event->user->email);
    }

    /**
     * @param PasswordReset $event
     */
    public function onUserPasswordReset(PasswordReset $event)
    {
        \Log::debug('User PasswordReset: ' . $event->user->email);
    }

    /**
     * @param Registered $event
     */
    public function onUserRegistered(Registered $event)
    {
        \Log::debug('User Registered: ' . $event->user->email);

        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }

    /**
     * @param Validated $event
     */
    public function onUserValidated(Validated $event)
    {
        \Log::debug('User Validated: ' . $event->user->email);
    }

    /**
     * @param Verified $event
     */
    public function onUserVerified(Verified $event)
    {
        \Log::debug('User Verified: ' . $event->user->email);
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            Attempting::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserAttempting'
        );

        $events->listen(
            Authenticated::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserAuthenticated'
        );

        $events->listen(
            Failed::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserFailed'
        );

        $events->listen(
            Lockout::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserLockout'
        );

        $events->listen(
            Login::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            Logout::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserLogout'
        );

        $events->listen(
            PasswordReset::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserPasswordReset'
        );

        $events->listen(
            Registered::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserRegistered'
        );

        $events->listen(
            Validated::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserValidated'
        );

        $events->listen(
            Verified::class,
            'App\Listeners\Auth\UserEventSubscriber@onUserVerified'
        );
    }
}
