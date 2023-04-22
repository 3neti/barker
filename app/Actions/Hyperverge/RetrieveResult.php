<?php

namespace App\Actions\Hyperverge;

use App\Events\Hyperverge\ResultRetrieved;
use Lorisleiva\Actions\Concerns\AsAction;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Exceptions\NotAutoApproved;
use JetBrains\PhpStorm\ArrayShape;
use App\Classes\Hyperverge;
use Illuminate\Support\Arr;
use App\Models\Checkin;

class RetrieveResult
{
    use AsAction;

    /**
     * @param Hyperverge $hyperverge
     */
    public function __construct(public Hyperverge $hyperverge){}

    /**
     * @param Checkin $checkin
     * @return bool
     */
    public function handle(Checkin $checkin): bool
    {
        $success = false;
        $body = ['transactionId' => $checkin->getAttribute('uuid')];
        tap($this->getHypervergeResponse($body), function ($response) use ($checkin, &$success) {
            if ($response->successful()) {
                $checkin->setData($response->json())->save();
                ResultRetrieved::dispatch($checkin);
                $success = true;
            }
        });

        return $success;
    }

    /**
     * @param Checkin $checkin
     */
    public function asJob(Checkin $checkin): void
    {
        $this->handle($checkin);
    }

    /**
     * @return string[]
     */
    #[ArrayShape(['transactionId' => "string", 'status' => "string"])]
    public function rules(): array
    {
        return [
            'transactionId' => 'required|uuid',
            'status' => 'required'
        ];
    }

    /**
     * @param ActionRequest $request
     * @return RedirectResponse
     * @throws NotAutoApproved
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        list($transactionId, $status) = array_values(Arr::only($request->all(), ['transactionId', 'status']));

        if ($status == 'auto_approved') {
            self::dispatch(Checkin::find($transactionId));

            return redirect()->route('contacts.show', ['contact' => $transactionId]);
        } else {
            throw new NotAutoApproved($transactionId, $status);
        }
    }

    /**
     * @param array $body
     * @return PromiseInterface|Response
     */
    protected function getHypervergeResponse(array $body): PromiseInterface|Response
    {
        return Http::withHeaders($this->hyperverge->headers())
            ->post($this->hyperverge->resultEndpoint(), $body);
    }
}
