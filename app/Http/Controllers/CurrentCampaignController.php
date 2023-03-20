<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class CurrentCampaignController extends Controller
{
    /**
     * Update the authenticated user's current campaign.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $campaign = app(Campaign::class)->findOrFail($request->campaign_id);

        if (! $request->user()->switchCampaign($campaign)) {
            abort(403);
        }

        return redirect(config('fortify.home'), 303);
    }
}
