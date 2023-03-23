<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\TeamSeeder;
use App\Models\{Team, User};
use Illuminate\Support\Arr;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(TeamSeeder::class);
});

dataset('attribs', function () {
   return [
       'Yari w/ Team' => [
           [
               'email' => 'sofia@hurtado.ph',
               'name' => 'Sofia Hurtado (Acme Corp.)',
               'amount' => 50000.00
           ]
       ],
       'Cheesecake' => [
           [
               'email' => 'fgphurtado@me.com',
               'name' => 'Francesca Hurtado',
               'amount' => 10000.00
           ]
       ],
   ];
});

test('paynamics webhook persists user from email, team from name and tops up credits', function (array $attribs) {
    $this->assertCount(2, Team::all());
    $response = $this->postJson('webhook-paynamics-paybiz', getPaynamicsPayload($attribs) , []);
    $response->assertSuccessful();
    $user = User::where(Arr::only($attribs, 'email'))->first();
    $name = Arr::get($attribs, 'name');
    $amount = Arr::get($attribs, 'amount');
    if ($team = extractTeamFromName($name)) {
        $this->assertCount(3, app(Team::class)->all());
    }
    else {
        $this->assertCount(2, app(Team::class)->all());
        $team = app(Team::class)->default()->name;
    }
    expect($user->name)->toBe($name);
    expect($user->currentTeam->name)->toBe($team);
    expect($user->balanceFloatNum)->toBe($amount);
})->with('attribs');


