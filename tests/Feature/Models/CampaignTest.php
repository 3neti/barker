<?php

use App\Events\CampaignAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Actions\AddCampaignMember;
use Database\Seeders\TeamSeeder;
use App\Models\{Campaign, Team, User};
use App\Actions\CreateCampaign;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Fortify\CreateNewUser;
use App\Classes\InviteCodes;
use App\Models\Enlistment;
use App\Actions\AddTeamCampaign;
use App\Actions\Jetstream\AddTeamMember;
use Illuminate\Support\Facades\DB;
use App\Models\Membership;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
Use Illuminate\Support\Facades\Event;

uses(WithFaker::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(TeamSeeder::class);
});

dataset('attribs', [
    [[['name' => 'Amelia Hurtado (Acme Corp.)', 'email' => 'amelia@hurtado.ph', 'amount' => 5000]]]
]);

dataset('campaigns', [
    [['Campaign 1', 'Campaign 2']]
]);

test('user campaign coverage', function ($attribs, $teamNames) {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $campaigns = [];
    foreach ($teamNames as $name) {
        $currentCampaign = app(CreateCampaign::class)->run($user1, compact('name'));
        expect($user1->isCurrentCampaign($currentCampaign))->toBeTrue();
        $campaigns [] = $currentCampaign;
    }
    expect($user1->isCurrentCampaign($campaigns[1]))->toBeTrue();
    expect($user1->currentCampaign)->toBeInstanceOf(Campaign::class);
    expect($user1->switchCampaign($campaigns[0]))->toBeTrue();
    expect($user1->isCurrentCampaign($campaigns[0]))->toBeTrue();

    expect($user1->ownedCampaigns)->toHaveCount(2);
    expect($user1->allCampaigns())->toHaveCount(2);
    expect($user1->campaigns)->toHaveCount(0);

    expect($user1->ownsCampaign($campaigns[0]))->toBeTrue();
    expect($user1->ownsCampaign($campaigns[1]))->toBeTrue();
    expect($user2->ownsCampaign($campaigns[0]))->toBeFalse();
    expect($user2->ownsCampaign($campaigns[1]))->toBeFalse();

    expect($user1->belongsToCampaign($campaigns[0]))->toBeTrue();
    expect($user1->belongsToCampaign($campaigns[1]))->toBeTrue();
    expect($user2->belongsToCampaign($campaigns[0]))->toBeFalse();
    expect($user2->belongsToCampaign($campaigns[1]))->toBeFalse();

    foreach ($campaigns as $currentCampaign) {
        $currentCampaign->users()->attach(
            $user1, ['role' => 'agent']
        );
    }
    expect($user1->fresh()->campaigns)->toHaveCount(2);
    expect($user2->fresh()->campaigns)->toHaveCount(0);
    app(AddCampaignMember::class)->run($user1, $campaigns[0], $user2->email, 'agent');
    expect($user2->fresh()->campaigns)->toHaveCount(1);
    expect($user2->fresh()->belongsToCampaign($campaigns[0]))->toBeTrue();
    expect($user1->fresh()->campaignRole($campaigns[0])->key)->tobe('owner');
    expect($user1->fresh()->campaignRole($campaigns[1])->key)->tobe('owner');
    expect($user2->fresh()->campaignRole($campaigns[0])->key)->tobe('agent');
    expect($user1->fresh()->hasCampaignRole($campaigns[0], 'anyRole'))->toBeTrue();
    expect($user2->fresh()->hasCampaignRole($campaigns[0], 'admin'))->toBeFalse();
    expect($user2->fresh()->hasCampaignRole($campaigns[0], 'agent'))->toBeTrue();
})->skip('The quick brown fox')
    ->with('attribs')
    ->with('campaigns');

test('team campaign coverage', function ($attribs, $teamNames) {
    Event::fake(CampaignAdded::class);
    expect(app(Membership::class)->all())->toHaveCount(1);
    $invite1 = app(InviteCodes::class)->create()->restrictUsageTo('fgphurtado@me.com')->save();
    $user1 = app(CreateNewUser::class)->create([
        'name' => 'Francesca Hurtado',
        'email' => 'fgphurtado@me.com',
        'invite_code' => $invite1->code,
        'password' => $password = $this->faker->password(8),
        'password_confirmation' => $password
    ]);
    $invite2 = app(InviteCodes::class)->create()->restrictUsageTo('sofia@hurtado.ph')->save();
    $user2 = app(CreateNewUser::class)->create([
        'name' => 'Sofia Hurtado',
        'email' => 'sofia@hurtado.ph',
        'invite_code' => $invite2->code,
        'password' => $password = $this->faker->password(8),
        'password_confirmation' => $password
    ]);
    expect($user1->currentCampaign)->toBeNull();
    $team1 = app(CreateTeam::class)->create($user1, ['name' => 'Team 1']);
    $team2 = app(CreateTeam::class)->create($user2, ['name' => 'Team 2']);
    /** just checking user and teams */
    expect(app(Membership::class)->all())->toHaveCount(3);
    expect($user1->currentTeam->id)->toBe($team1->id);
    expect($user2->currentTeam->id)->toBe($team2->id);

    $campaigns = [];
    foreach ($teamNames as $name) {
        $currentCampaign = app(CreateCampaign::class)->run($user1, compact('name'));
        expect($team1->belongsToCampaign($currentCampaign))->toBeTrue();
        $campaigns [] = $currentCampaign;
    }

    /** HasCampaign Trait Function Tests */
    $team1 = $team1->fresh();
    expect($team1->isCurrentCampaign($campaigns[1]))->toBeTrue();
    expect($team1->currentCampaign)->toBeInstanceOf(Campaign::class);
    expect($team1->switchCampaign($campaigns[0]))->toBeTrue();
    expect($team1->isCurrentCampaign($campaigns[0]))->toBeTrue();

    expect($team1->ownedCampaigns)->toHaveCount(2);
    expect($team1->allCampaigns())->toHaveCount(2);
    expect($team1->campaigns)->toHaveCount(0);

    expect($team1->ownsCampaign($campaigns[0]))->toBeTrue();
    expect($team1->ownsCampaign($campaigns[1]))->toBeTrue();
    expect($team2->ownsCampaign($campaigns[0]))->toBeFalse();
    expect($team2->ownsCampaign($campaigns[1]))->toBeFalse();

    expect($team1->belongsToCampaign($campaigns[0]))->toBeTrue();
    expect($team1->belongsToCampaign($campaigns[1]))->toBeTrue();
    expect($team2->belongsToCampaign($campaigns[0]))->toBeFalse();
    expect($team2->belongsToCampaign($campaigns[1]))->toBeFalse();

    /** although there are 2 campaigns persisted */
    expect(app(Campaign::class)->all())->toHaveCount(2);

    /** the enlistment pivot (campaign_team table) has no records yet */
    expect(app(Enlistment::class)->all())->toHaveCount(0);

    /** therefore team1 campaigns is zero */
    expect($team1->campaigns)->toHaveCount(0);

    /** so if user1 has 1 team */
    expect($user1->teams)->toHaveCount(1);
    expect($user1->currentTeam->id)->toBe($team1->id);

    /** user1 has no enlistments and no current campaign context */
    expect($user1->campaigns)->toHaveCount(0);
//    expect($user1->currentCampaign->id)->toBe($user1->currentTeam->currentCampaign->id);

    /** now if the team1 campaigns are enlisted */
    foreach ($campaigns as $currentCampaign) {
        app(AddTeamCampaign::class)->run($team1, $currentCampaign, 'agent');
    }

    /** note: the event CampaignAdded was disabled */
    $team1 = $team1->fresh();
    $user1= $user1->fresh();

    /** the enlistment pivot (campaign_team table) should have 2 records */
    expect(app(Enlistment::class)->all())->toHaveCount(2);

    /** and team1 campaigns consequently have 2 records */
    expect($team1->campaigns)->toHaveCount(2);

    /** therefore user1 should have 2 campaigns */
    expect($user1->campaigns)->toHaveCount(2);
    expect($user1->currentCampaign->id)->toBe($user1->currentTeam->currentCampaign->id);

    /** team2 does not have any campaigns */
    expect($team2->campaigns)->toHaveCount(0);

    /** so user2 of team also does not have any campaigns */
    expect($user2->campaigns)->toHaveCount(0);

    /** lets introduce a new agent i.e. user3 under team1 */
    $invite3 = app(InviteCodes::class)->create()->restrictUsageTo('adphurtado@me.com')->save();
    $user3 = app(CreateNewUser::class)->create([
        'name' => 'Amelia Hurtado',
        'email' => 'adphurtado@me.com',
        'invite_code' => $invite3->code,
        'password' => $password = $this->faker->password(8),
        'password_confirmation' => $password
    ]);

    /** initially user3 has no campaigns yet */
    expect($user3->currentTeam->campaigns)->toHaveCount(0);
    expect($user3->campaigns)->toHaveCount(0);
    app(AddsTeamMembers::class)->add($user1, $team1, $user3->email, 'agent');
    $user3 = $user3->fresh();

    /** even after user3 is added to the team */
    $defaultTeam = app(Team::class)->default();
    expect($defaultTeam->campaigns)->toHaveCount(0);
    expect($user3->currentTeam->id)->toBe($defaultTeam->id);
    expect($user3->campaigns)->toHaveCount(0);

    /** but once user3 switches to team1, user3 now has 2 campaigns */
    $user3->switchTeam($team1);
    expect($user3->fresh()->campaigns)->toHaveCount(2);
})
    ->with('attribs')
    ->with('campaigns');

