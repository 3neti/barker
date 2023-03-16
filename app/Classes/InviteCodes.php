<?php

namespace App\Classes;

use Junges\InviteCodes\InviteCodes as BaseClass;
use App\Models\{Invite, Team, User};
use Illuminate\Support\{Arr, Str};

class InviteCodes extends BaseClass
{
    protected array $data = ['role' => 'agent'];

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setFrom(User $user): self
    {
        Arr::set($this->data, 'from_id', $user->id);
        if (!Arr::get($this->data, 'team_id')) {
            $this->setTeam($user->currentTeam);
        }

        return $this;
    }

    public function setTeam(Team $team): self
    {
        Arr::set($this->data, 'team_id', $team->id);

        return $this;
    }

    public function setRole(string $role): self
    {
        Arr::set($this->data, 'role', $role);

        return $this;
    }

    /** @inheritdoc */
    public function save(): Invite
    {
        $model = app(config('invite-codes.models.invite_model', Invite::class));

        do {
            $code = Str::upper(Str::random(16));
        } while ($model->where('code', $code)->first() instanceof $model);

        return $model->create([
            'code' => $code,
            'to' => $this->to,
            'data' => $this->data,
            'uses' => 0,
            'expires_at' => $this->expires_at ?? null,
            'max_usages' => $this->max_usages ?? null,
        ]);
    }
}
