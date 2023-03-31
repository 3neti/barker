<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCheckinRequest;
use App\Http\Requests\StoreCheckinRequest;
use Laravel\Jetstream\RedirectsActions;
use Illuminate\Http\RedirectResponse;
use Laravel\Jetstream\Jetstream;
use App\Actions\CreateCheckin;
use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Classes\Barker;
use Inertia\Response;
use Inertia\Inertia;

class CheckinController extends Controller
{
    use RedirectsActions;

    public function index(): Response
    {
        return Inertia::render('Checkins/Index', [
            'checkins' => app(Checkin::class)
//                ->with('agent:id,name')
//                ->with('person:id,mobile,handle')
//                ->whereBelongsTo(auth()->user(), 'agent')
//                ->latest()
                ->get()
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
        $action->dispatch($request->user(), $request->all());

        return $this->redirectPath($action);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkin $checkin)
    {
        //
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
