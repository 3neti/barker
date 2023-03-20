<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;
use App\Actions\CreateCampaign;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Inertia\Response;
use Laravel\Jetstream\RedirectsActions;

class CampaignController extends Controller
{
    use RedirectsActions;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the campaign creation screen.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
//        Gate::authorize('create', Jetstream::newTeamModel());

        return Jetstream::inertia()->render($request, 'Campaigns/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCampaignRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        $action = app(CreateCampaign::class);
        $action->run($request->user(), $request->all());

//        return redirect(route('dashboard'));
        return $this->redirectPath($action);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        //
    }
}
