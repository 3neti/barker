<?php

namespace App\Actions;

use Laravel\Fortify\Contracts\{CreatesNewUsers, RegisterResponse};
use Illuminate\Contracts\Auth\Authenticatable;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Guard;
use Junges\InviteCodes\InviteCodes;
use Illuminate\Http\Request;

class RegisterUser
{
    use AsAction;

    public function __construct(protected CreatesNewUsers $creator, protected Guard $guard){}

    public function handle(array $credentials, bool $session = true): Authenticatable
    {
        event(new Registered($user = $this->creator->create($credentials)));
        if ($session) {
            $this->guard->login($user);
        }
        else {
            $this->guard->setUser($user);
        }

        app(InviteCodes::class)->redeem($credentials['invite_code']);

        return $user;
    }

    public function asController(Request $request, CreatesNewUsers $creator): RegisterResponse
    {
        $this->handle($request->all());
        $this->creator = $creator;

        return app(RegisterResponse::class);
    }

    public function asJob(array $credentials, bool $session = true)
    {
        $this->handle($credentials, $session);
    }
}
