<?php

namespace App\Mail;

use Laravel\Jetstream\TeamInvitation as TeamInvitationModel;
use Laravel\Jetstream\Mail\TeamInvitation as BaseMailable;
use Illuminate\Support\Facades\URL;

class TeamInvitation extends BaseMailable
{
    protected string $inviteCode;

    public function __construct(TeamInvitationModel $invitation, $inviteCode)
    {
        parent::__construct($invitation);

        $this->inviteCode = $inviteCode;
    }

    public function build()
    {
        return $this->markdown('emails.team-invitation', [
            'acceptUrl' => URL::signedRoute('team-invitations.accept', [
                'invitation' => $this->invitation,
            ]),
            'inviteCode' => $this->inviteCode
        ])->subject(__('Team Invitation'));
    }
}
