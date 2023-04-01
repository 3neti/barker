<?php

namespace App\Actions\Hyperverge;

use App\Events\Hyperverge\ResultRetrieved;
use App\Exceptions\NotAutoApproved;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use App\Classes\Hyperverge;
use Illuminate\Support\Arr;
use App\Models\Checkin;

class RetrieveResult
{
    use AsAction;

    public function __construct(public Hyperverge $hyperverge){}

    public function handle(Checkin $checkin): bool
    {
        $body = ['transactionId' => $checkin->getAttribute('uuid')];
        $response = Http::withHeaders($this->hyperverge->headers())->post($this->hyperverge->resultEndpoint(), $body);
        if ($response->successful()) {
            $checkin->setAttribute('data', $response->json());
            $checkin->save();
            ResultRetrieved::dispatch($checkin);
        }

        return (null !== $checkin->getAttribute('data'));
    }

    public function asJob(Checkin $checkin)
    {
        $this->handle($checkin);
    }

    #[ArrayShape(['transactionId' => "string", 'status' => "string"])]
    public function rules(): array
    {
        return [
            'transactionId' => 'required|uuid',
            'status' => 'required'
        ];
    }

    /**
     * @throws NotAutoApproved
     */
    public function asController(ActionRequest $request)
    {
        $status = Arr::get($request->all(), 'status');

        if ($status == 'auto_approved') {
            $uuid = Arr::get($request->all(), 'transactionId');
            $checkin = Checkin::find($uuid);
            self::dispatch($checkin);
            return redirect()->route('contacts.show', ['contact' => $uuid]);
        } else {
            throw new NotAutoApproved('');
        }
    }
}
