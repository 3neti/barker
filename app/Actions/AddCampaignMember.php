<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Events\AddingCampaignMember;
use App\Events\CampaignMemberAdded;
use Laravel\Jetstream\Rules\Role;
use Laravel\Jetstream\Jetstream;
use App\Models\Campaign;
use App\Models\User;
use Closure;

class AddCampaignMember
{
    use AsAction;

    /**
     * Add a new campaign member to the given campaign.
     */
    public function handle(User $user, Campaign $campaign, string $email, string $role = null): void
    {
        Gate::forUser($user)->authorize('addCampaignMember', $campaign);

        $this->validate($campaign, $email, $role);

        $newCampaignMember = Jetstream::findUserByEmailOrFail($email);

        AddingCampaignMember::dispatch($campaign, $newCampaignMember);

        $campaign->users()->attach(
            $newCampaignMember, ['role' => $role]
        );

        CampaignMemberAdded::dispatch($campaign, $newCampaignMember);
    }

    /**
     * Validate the add member operation.
     */
    protected function validate(Campaign $campaign, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnCampaign($campaign, $email)
        )->validateWithBag('addCampaignMember');
    }

    /**
     * Get the validation rules for adding a campaign member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(): array
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role' => Jetstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the campaign.
     */
    protected function ensureUserIsNotAlreadyOnCampaign(Campaign $campaign, string $email): Closure
    {
        return function ($validator) use ($campaign, $email) {
            $validator->errors()->addIf(
                $campaign->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the campaign.')
            );
        };
    }
}
