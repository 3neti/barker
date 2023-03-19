<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\{DB, Hash, Validator};
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Actions\Jetstream\AddTeamMember;
use Laravel\Jetstream\Jetstream;
use App\Models\{Team, User};

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     * Invite Code is required, no walk-ins...
     * ...unless probably one pays in advance -
     * this logic is not here
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'exists:invites,to'],
            'invite_code' => ['required', 'string', 'min:3', 'max:125','exists:invites,code'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) {
                if (!$user->isSystem()) {
                    $this->attachToDefaultTeam($user);
                }
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }

    protected function attachToDefaultTeam(User $user): void
    {
        if (($system = app(User::class)->system()) && ($default = app(Team::class)->default())) {
            app(AddTeamMember::class)->add($system, $default, $user->email, User::defaultRole());
            $user->switchTeam($default);
        }
    }
}
