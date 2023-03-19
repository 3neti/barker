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
    const GENERAL_TEAM_NDX = 'general';

    public function run()
    {
        return DB::transaction(function () {
            return tap(app(User::class)->create($this->adminAttributes()), function (User $admin) {
                $teams = [];
                foreach ($this->seededTeams() as $ndx => $attribs) {
                    $team = app(CreateTeam::class)->create($admin, $attribs);
                    $admin->ownedTeams()->save($team);
                    $teams[$ndx] = $team;
                }
                tap($teams[self::GENERAL_TEAM_NDX], function ($generalTeam) use ($admin) {;
                    $generalTeam->users()->attach($admin, User::adminRoleAttribute());
                    $admin->switchTeam($generalTeam);
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

    protected function defaultTransactionMeta(): array
    {
        return ['message' => 'initial deposit', 'from' => 'system'];
    }
}
