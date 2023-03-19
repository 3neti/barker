<?php

namespace App\Providers;

use App\Actions\Webhook\RemoveUserFromStandby;
use App\Events\NewTeamFromTopup;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;
use Junges\InviteCodes\Events\InviteRedeemedEvent;
use App\Observers\{TeamObserver, UserObserver};
use App\Listeners\{TempListener, JoinTeam};
use Illuminate\Auth\Events\Registered;
use App\Models\{Team, User};

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        User::class => [UserObserver::class],
        Team::class => [TeamObserver::class],
    ];

    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        InviteRedeemedEvent::class => [
//            JoinTeam::class
        ],
        BalanceUpdatedEventInterface::class => [
            TempListener::class
        ],
        NewTeamFromTopup::class => [
            RemoveUserFromStandby::class
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
