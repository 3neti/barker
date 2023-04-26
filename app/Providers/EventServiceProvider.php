<?php

namespace App\Providers;

use App\Events\{CampaignAdded,
    CheckinAdded,
    Hyperverge\ResultProcessed,
    Hyperverge\ResultRetrieved,
    Hyperverge\TransactionDisapproved,
    Hyperverge\URLGenerated};
use App\Actions\Hyperverge\{GenerateURL,
    ProcessResult,
    SendCampaignMissives,
    SendCampaignNotifications,
    SendCheckinNotification,
    UpdateCheckinUrl};
use App\Actions\{AddTeamCampaign, CreateCampaignItems, CreateCampaignMissives, CreateCampaignProfiles};
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Observers\{CampaignObserver, CheckinObserver, TeamObserver, UserObserver};
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;
use Junges\InviteCodes\Events\InviteRedeemedEvent;
use App\Models\{Campaign, Checkin, Team, User};
use App\Actions\Webhook\RemoveUserFromStandby;
use App\Listeners\{TempListener, JoinTeam};
use Illuminate\Auth\Events\Registered;
use App\Events\TeamMemberAssigned;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [
        User::class => [UserObserver::class],
        Team::class => [TeamObserver::class],
        Campaign::class => [CampaignObserver::class],
        Checkin::class => [CheckinObserver::class],
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
        TeamMemberAssigned::class => [
            RemoveUserFromStandby::class
        ],
        CampaignAdded::class => [
            AddTeamCampaign::class,
            CreateCampaignItems::class,
            CreateCampaignMissives::class,
            CreateCampaignProfiles::class,
        ],
        CheckinAdded::class => [
            GenerateURL::class
        ],
        URLGenerated::class => [
            UpdateCheckinUrl::class,
            SendCampaignMissives::class,
        ],
        ResultRetrieved::class => [
            ProcessResult::class
        ],
        ResultProcessed::class => [
            SendCampaignNotifications::class,
            SendCheckinNotification::class,
        ],
        TransactionDisapproved::class => [

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
