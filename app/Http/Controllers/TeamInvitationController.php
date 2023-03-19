<?php

namespace App\Http\Controllers;

use Laravel\Jetstream\Http\Controllers\TeamInvitationController as BaseController;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;

class TeamInvitationController extends BaseController
{
    /**
     * Accept a team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $invitationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, $invitationId)
    {
        $model = Jetstream::teamInvitationModel();

        $invitation = $model::whereKey($invitationId)->firstOrFail();

        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->email,
            $invitation->role
        );

        Auth::user()->switchTeam($invitation->team);

        $invitation->delete();

        return redirect(config('fortify.home'))->banner(
            __('Magaling! You have accepted the invitation to join the :team team.', ['team' => $invitation->team->name]),
        );
    }
}
