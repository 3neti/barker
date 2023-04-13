<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCheckinRequest;
use App\Http\Requests\StoreCheckinRequest;
use Laravel\Jetstream\RedirectsActions;
use Illuminate\Http\RedirectResponse;
use Laravel\Jetstream\Jetstream;
use App\Enums\HypervergeModule;
use App\Actions\CreateCheckin;
use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Classes\Barker;
use Inertia\Response;
use Inertia\Inertia;

class CheckinController extends Controller
{
    use RedirectsActions;

    public function index(Request $request): Response
    {
        return Inertia::render('Checkins/Index', [
            'checkins' => Checkin::with('agent:id,name')
                ->with('person:id,mobile,handle')
                ->whereBelongsTo(auth()->user(), 'agent')
                ->whereBelongsTo(auth()->user()->currentCampaign, 'campaign')
                ->latest()->get(),
            'campaign' => $campaign = $request->user()->currentCampaign,
            'type' => $request->user()->currentTeam->campaignType($campaign),
            'channels' => $campaign->campaignItems->pluck('channel')->all()
        ]);
    }

    public function create(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Checkins/Create', [
            'campaign' => $campaign = $request->user()->currentCampaign,
            'type' => $request->user()->currentTeam->campaignType($campaign)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckinRequest $request): RedirectResponse
    {
        $action = app(CreateCheckin::class);
        $agent = $request->user();
        $action->dispatch($agent, $request->all());

        return to_route('checkins.show', ['checkin' => $agent->fresh()->current_checkin_uuid]);
//        return $this->redirectPath($action);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkin $checkin): Response
    {
        return Inertia::render('Checkins/Show', [
            'checkin' => fn() => $checkin?->only('uuid', 'url', 'QRCodeURI', 'agent', 'campaign', 'data_retrieved_at'),
            'dataRetrieved' => $checkin?->dataRetrieved(),
            'fieldsExtracted' => $checkin?->data?->getFieldsExtracted(),
            'idChecks' => $checkin?->data?->getIdChecks(),
            'selfieChecks' => $checkin?->data?->getSelfieChecks(),
            'idImageUrl' => $checkin?->data?->getIdImageUrl(),
            'selfieImageUrl' => $checkin?->data?->getSelfieImageUrl(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkin $checkin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckinRequest $request, Checkin $checkin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkin $checkin)
    {
        //
    }
}
