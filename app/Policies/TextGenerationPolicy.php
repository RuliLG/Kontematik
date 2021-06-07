<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TextGenerationPolicy
{
    use HandlesAuthorization;

    public const MAX_FREE_TOKENS = 10000;
    public const MAX_FREE_DAYS = 7;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function generate(User $user)
    {
        if ($user->is_admin) {
            return true;
        }

        if (!$user->subscribed()) {
            // If the user is not subscribed, they will only be allowed to make
            // requests up to MAX_FREE_TOKENS in MAX_FREE_DAYS since their account verification.
            return $user->monthly_tokens < self::MAX_FREE_TOKENS
                && $user->email_verified_at >= now()->subDays(self::MAX_FREE_DAYS)->setTime(23, 59, 59);
        }

        // If the user is subscribed, we will find their subscription and check if they still have
        // tokens left
        $subscription = $user->subscription();
        foreach (config('spark.billables.user.plans') as $plan) {
            if (in_array($subscription->stripe_plan, [$plan['monthly_id'], $plan['yearly_id']])) {
                return $user->monthly_tokens < $plan['tokens'];
            }
        }

        return false;
    }
}
