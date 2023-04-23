<?php

namespace App\Actions;

use Propaganistas\LaravelPhone\Rules\Phone as PhoneRule;
use Illuminate\Support\Facades\{Gate, Validator};
use App\Events\{AddingCheckin, CheckinAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Checkin, Contact, User};
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;

class CreateCheckin
{
    use AsAction;

    /**
     * TODO: use id instead of uuid for the relation betweeen checkin and person
     * TODO: make it contact instead of person
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(User $agent, array $input = []): Checkin
    {
        //remove nulls, hard to validate mobile if null :-)
        $input = array_filter($input, static function ($var) {
            return $var !== null;
        });
        Validator::make($input, $this->rules($input))->validateWithBag('createCheckin');
        AddingCheckin::dispatch($agent);

        $checkin = tap(app(Checkin::class)->make($input), function ($checkin) use ($agent, $input) {
            $checkin->setAgent($agent);
            $checkin->setCampaign($agent->currentCampaign);
            $checkin->save();
            $agent->switchCheckin($checkin);
            try {
                $attributes = Arr::only($input, ['mobile', 'handle']);
                $contact = $checkin->contact()->create($attributes);
            }
            catch (QueryException $exception) {
                $error_code = $exception->errorInfo[1];
                if ($error_code == 1062){
                    $contact = Contact::fromMobile(Arr::get($input, 'mobile'));
                    $contact->checkin_uuid = $checkin->uuid;//TODO: create a contact_checkin table
                    $contact->save();
                }
            }
            $checkin->setPerson($contact);
            $checkin->save();
        });

        CheckinAdded::dispatch($checkin);

        return $checkin;
    }

    public function asJob(User $agent, array $input)
    {
        $this->handle($agent, $input);
    }

    protected function rules(array $input): array
    {
        //TODO: add more countries
        return array_filter([
            'mobile' => [(new PhoneRule)->mobile()->country('PH', 'IN', 'US')],
        ]);
    }
}
