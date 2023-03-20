<?php

namespace App\Http\Middleware;

use Junges\InviteCodes\Exceptions\InviteWithRestrictedUsageException;
use Junges\InviteCodes\Exceptions\RouteProtectedByInviteCodeException;
use Junges\InviteCodes\Exceptions\InvalidInviteCodeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Junges\InviteCodes\Contracts\InviteContract;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class InviteCode
{
    /**
     * Handle an incoming request.
     *
     * @throws RouteProtectedByInviteCodeException
     * @throws InvalidInviteCodeException
     * @throws InviteWithRestrictedUsageException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->has('invite_code')) {
            throw new RouteProtectedByInviteCodeException('This route is accessible only by using invite codes', Response::HTTP_FORBIDDEN);
        }

        $invite_code = $request->input('invite_code');
        $invite_model = app(config('invite-codes.models.invite_model'));

        try {
            $invite = $invite_model->where('code', $invite_code)->firstOrFail();
            assert($invite instanceof InviteContract);
        } catch (ModelNotFoundException) {
            throw new InvalidInviteCodeException('Your invite code is invalid', Response::HTTP_FORBIDDEN);
        }

        if (! $invite->hasRestrictedUsage()) {
            return $next($request);
        }

        if ($invite->usageRestrictedToEmail($request->{config('invite-codes.user.email_column')})) {
            return $next($request);
        }

        throw new InviteWithRestrictedUsageException('This invite code is not for you.', Response::HTTP_FORBIDDEN);
    }
}
