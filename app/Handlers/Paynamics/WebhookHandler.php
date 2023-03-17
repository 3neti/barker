<?php

namespace App\Handlers\Paynamics;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use App\Actions\{RegisterUser, TopupUserWallet};
use App\Models\{Invite, Team, User};
use Illuminate\Pipeline\Pipeline;
use Laravel\Jetstream\Jetstream;
use App\Classes\InviteCodes;
use Illuminate\Support\Arr;

class WebhookHandler extends ProcessWebhookJob
{
    const NO_SESSIONS = false;

    const DEFAULT_USER_WALLET = 'default';

    public int $timeout = 120;

    protected User $user;

    public function handle()
    {
        /** get customer attributes from webhook payload */
        $attribs = Arr::get($this->webhookCall, 'payload.customer_info');

        /** check if user exists */
        try {
            $this->setUser(Jetstream::findUserByEmailOrFail($email = Arr::get($attribs, 'email')));
        }
        catch (ModelNotFoundException $exception) {
            /** generate invite code for attachment to "Standby Team"  */
            $invite = $this->generateInvite($email);

            /** process and clean attributes, include invite code in attribs */
            $attribs = $this->processAttribs($attribs, $invite->code);

            /** persist user from attributes */
            $this->user = RegisterUser::run($attribs, self::NO_SESSIONS);
        }
        catch (\Exception $exception) {
            dd('Check this out! ' . self::class . ' ' .  $exception->getMessage());
        }
        finally {
            /** credit amount to user wallet */
            TopupUserWallet::run($this->user, $attribs['amount'], self::DEFAULT_USER_WALLET, [
                'deposit' => ['to' => 'user credits'],
                'withdraw' => ['from' => 'Paynamics']
            ]);
        }
    }

    protected function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    protected function getStandbyTeam(): Team
    {
        return app(Team::class)->find(2);
    }

    protected function generateInvite(string $email): Invite
    {
        return app(InviteCodes::class)->create()
            ->restrictUsageTo($email)
            ->setTeam($this->getStandbyTeam())
            ->save();
    }

    protected function processAttribs($attribs, $inviteCode): array
    {
        return app(Pipeline::class)->send($attribs)
            ->through(
                function ($attribs, $next) use ($inviteCode) {
                    /** add necessary key value pairs to attributes */
                    Arr::set($attribs, 'invite_code', $inviteCode);
                    Arr::set($attribs, 'password', '#Password1');
                    Arr::set($attribs, 'password_confirmation', '#Password1');

                    return $next($attribs);
                },
                function ($attribs, $next) use ($inviteCode) {
                    /** clean data - remove multiple white spaces */
                    foreach ($attribs as $key => $value) {
                        $attribs[$key] = trim(preg_replace('/\s+/', ' ', $value));
                    }

                    return $next($attribs);
                }
            )->thenReturn();
    }
}
