<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\{DB, Hash};
use App\Actions\Jetstream\CreateTeam;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Seeder;
use App\Models\User;

class TeamSeeder extends Seeder
{
    const CONFIRMED = 1;
    const DEFAULT_TEAM_NDX = 'default';

    public function run()
    {
        return DB::transaction(function () {
            return tap(app(User::class)->create($this->adminAttributes()), function (User $admin) {
                $teams = [];
                foreach ($this->seededTeams() as $ndx => $name) {
                    $team = app(CreateTeam::class)->create($admin, compact('name'));
                    $admin->ownedTeams()->save($team);
                    $teams[$ndx] = $team;
                }
                tap($teams[self::DEFAULT_TEAM_NDX], function ($defaultTeam) use ($admin) {
                    $defaultTeam->users()->attach($admin, $this->adminRoleAttribute());
                    $admin->switchTeam($defaultTeam);
                });
            })->depositFloat(...$this->depositArgs());
        });
    }

    #[ArrayShape(['name' => "mixed", 'email' => "mixed", 'password' => "string"])] protected function adminAttributes(): array
    {
        $attribs = config('domain.seeds.user.system');

        return [
            'name' => $attribs['name'],
            'email' => $attribs['email'],
            'password' => Hash::make($attribs['password']),
        ];
    }

    #[ArrayShape(['role' => "mixed"])] protected function adminRoleAttribute(): array
    {
        return ['role' => config('domain.seeds.user.system.role')];
    }

    protected function seededTeams(): array
    {
        return config('domain.seeds.teams');
    }

    protected function depositArgs(): array
    {
        return [
            $this->defaultWalletSlug(),
            $this->defaultTransactionMeta(),
            self::CONFIRMED
        ];
    }

    protected function defaultWalletSlug(): string
    {
        return config('domain.seeds.wallet.default');
    }

    #[ArrayShape(['message' => "string", 'from' => "string"])] protected function defaultTransactionMeta(): array
    {
        return ['message' => 'initial deposit', 'from' => 'system'];
    }
}
