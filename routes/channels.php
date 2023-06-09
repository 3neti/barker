<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Checkin;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('wallet.holder.{holderId}', function ($user, $holderId) {
    return (int) $user->id === (int) $holderId;
});

//Broadcast::channel('agent.{agentId}', function ($user, $agentId) {
////    return true;
//    return (int) $user->id === (int) $agentId;
//});

//Broadcast::channel('checkin.{transactionId}', function ($user, $transactionId) {
//    return $user->is(app(Checkin::class)->find($transactionId)->agent);
//});

Broadcast::channel('checkin.{checkin}', function ($user, Checkin $checkin) {
    return $user->is($checkin->agent);
});
