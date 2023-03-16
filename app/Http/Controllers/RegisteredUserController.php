<?php

namespace App\Http\Controllers;

use Laravel\Fortify\Http\Controllers\RegisteredUserController as BaseController;
use Laravel\Fortify\Contracts\{CreatesNewUsers, RegisterResponse};
use Junges\InviteCodes\Facades\InviteCodes;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisteredUserController extends BaseController
{
    /**
     * Create a new registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Laravel\Fortify\Contracts\RegisterResponse
     */
    public function store(Request $request,
                          CreatesNewUsers $creator): RegisterResponse
    {
        event(new Registered($user = $creator->create($request->all())));

        $this->guard->login($user);

        InviteCodes::redeem($request->get('invite_code'));

        return app(RegisterResponse::class);
    }
}
