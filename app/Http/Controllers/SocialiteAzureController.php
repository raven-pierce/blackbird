<?php

namespace App\Http\Controllers;

use App\Models\SocialiteProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAzureController extends Controller
{
    /**
     * Redirect the user to the provider authentication page
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('azure')->redirect();
    }

    /**
     * Obtain the user information from the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $socialiteUser = Socialite::driver('azure')->user();

        $user = User::whereRelation('socialiteProfiles', 'provider', 'azure')->whereRelation('socialiteProfiles', 'provider_id', $socialiteUser->getId())->first();

        if ($user) {
            auth()->login($user, true);

            return redirect()->route('dashboard');
        } else {
            $user = User::whereRelation('profile', 'azure_email', $socialiteUser->getEmail())->first();

            SocialiteProfile::updateOrCreate([
                'user_id' => $user->id,
                'provider' => 'azure',
                'provider_id' => $socialiteUser->getId(),
            ]);

            auth()->login($user, true);

            return redirect()->route('dashboard');
        }
    }
}
