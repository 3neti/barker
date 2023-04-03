<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user' => function () use ($request) {
                if (! $user = $request->user()) {
                    return;
                }

                $userHasTeamFeatures = Jetstream::userHasTeamFeatures($user);

                /** magic eager loading */
                if ($user && $userHasTeamFeatures) {
                    $user->currentTeam;
                    $user->currentCampaign;
                    $user->currentCheckin;
                }

                return array_merge($user->toArray(), array_filter(
                    [
                        'teams' => $userHasTeamFeatures ? $user->teams->values() : null,
                        'all_teams' => $userHasTeamFeatures ? $user->allTeams()->values() : null,
                        'campaigns' => $user->campaigns?->values() ?? null,//added the null, not yet tested, you can omit the ?? null
                    ],
                   ),
                    [
                        'balanceFloat' => $user->getWallet('default')->balanceFloat,
                        'balanceUpdatedAt' => $user->getWallet('default')->updated_at
                    ]
                );
            }
        ]);
    }
}
