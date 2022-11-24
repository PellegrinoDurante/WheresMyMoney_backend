<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function redirect(string $driver): RedirectResponse|Response
    {
        return Socialite::driver($driver)
            ->with(['institutionId' => 'TODO'])
            ->redirect();
    }

    public function callback(string $driver): RedirectResponse
    {
        /** @var User $user */
        $user = Socialite::driver($driver)->user();

        switch ($driver) {
            case 'nordigen':
                AccessToken::create([
                    'user_id' => \Auth::id(),
                    'name' => '',
                    'type' => AccessToken::TYPE_BANK,
                    'provider' => AccessToken::PROVIDER_BANK,
                    'access_token' => $user->token,
                    'expires_in' => $user->expiresIn,
                ]);

                break;
        }

        return redirect('/');
    }
}
