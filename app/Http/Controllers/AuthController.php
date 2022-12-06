<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class AuthController extends Controller
{
    public function redirect(string $driver, Request $request): RedirectResponse|Response
    {
        return Socialite::driver($driver)
            ->with([
                'institutionId' => $request->get('institutionId'),
                'name' => $request->get('name'),
            ])
            ->redirect();
    }

    public function callback(string $driver, Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Socialite::driver($driver)->user();

        switch ($driver) {
            case 'nordigen':
                AccessToken::create([
                    'user_id' => Auth::id(),
                    'name' => $request->get('name'),
                    'type' => AccessToken::TYPE_BANK,
                    'provider' => AccessToken::PROVIDER_BANK,
                    'access_token' => $user->token,
                    'expires_in' => $user->expiresIn,
                ]);

                break;
        }

        return redirect('/admin');
    }
}
