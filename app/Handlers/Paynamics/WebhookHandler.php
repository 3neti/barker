<?php

namespace App\Handlers\Paynamics;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Illuminate\Validation\ValidationException;
use App\Actions\Webhook\UpdateUserWallet;
use Illuminate\Support\Facades\Bus;
use Illuminate\Pipeline\Pipeline;
use App\Classes\InviteCodes;
use Illuminate\Support\Arr;
use App\Models\Team;
use App\Actions\{
    RegisterUser,
    Webhook\AssignUserTeam,
    Webhook\ResetUserPassword};

class WebhookHandler extends ProcessWebhookJob
{
    const NO_SESSIONS = false;

    public int $timeout = 120;

    public function handle()
    {
        /** if user exists, update wallet */
        try {
            $attribs = Arr::get($this->webhookCall, 'payload.customer_info');
            UpdateUserWallet::dispatch($attribs);
        }
        catch (ValidationException $exception) {
            $attribs = $this->getAttribs();
            /** persist user from attributes */
            Bus::chain([
                RegisterUser::makeJob($attribs, self::NO_SESSIONS),
                ResetUserPassword::makeJob($attribs),
                AssignUserTeam::makeJob($attribs),
                UpdateUserWallet::makeJob($attribs),
            ])->dispatch();
        }
        catch (\Exception $exception) {
            dd('Check this out! ' . self::class . ' ' .  $exception->getMessage());
        }
    }

    protected function getAttribs(): array
    {
        /** get customer attributes from webhook payload */
        $attribs = Arr::get($this->webhookCall, 'payload.customer_info');

        return app(Pipeline::class)->send($attribs)
            ->through(
                /** generate invite code for attachment to "Standby Team"  */
                function ($attribs, $next) {
                    $email = Arr::get($attribs, 'email');
                    $invite = app(InviteCodes::class)
                        ->create()
                        ->restrictUsageTo($email)
                        ->setTeam(app(Team::class)->default())
                        ->save();
                    Arr::set($attribs, 'invite_code', $invite->code);

                    return $next($attribs);
                },
                /** capture team name from sender name */
                function ($attribs, $next) {

                    $name = $attribs['name'];
                    $team  = extractTeamFromName($name);
                    if (null != $team) {
                        $attribs['name'] = $name;
                        $attribs['team'] = $team;
                    }
//                    $attribs['name'] = $name;
//                    $text = $attribs['name'];
//                    if (preg_match("/(?<name>.*\b).*\((?<team>.*)\)/", $text, $matches)) {
//                        $attribs['name'] = $matches['name'];
//                        $attribs['team'] = $matches['team'];
//                    }

                    return $next($attribs);
                },
                /** add necessary key value pairs to attributes */
                function ($attribs, $next) {
                    $password = Arr::get($attribs, 'invite_code');
                    Arr::set($attribs, 'password',  $password);
                    Arr::set($attribs, 'password_confirmation',  $password);

                    return $next($attribs);
                },
                /** clean data - remove multiple white spaces */
                function ($attribs, $next) {
                    foreach ($attribs as $key => $value) {
                        $attribs[$key] = trim(preg_replace('/\s+/', ' ', $value));
                    }

                    return $next($attribs);
                }
            )->thenReturn();
    }
}
