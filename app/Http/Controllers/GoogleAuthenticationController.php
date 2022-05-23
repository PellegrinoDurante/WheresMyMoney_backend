<?php

namespace App\Http\Controllers;

use App\Services\GoogleAuthenticationService;
use Auth;
use Google\Exception;
use Google\Service\Gmail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoogleAuthenticationController extends Controller
{
    public function __construct(private GoogleAuthenticationService $googleAuthenticationService)
    {
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function loginPage(): RedirectResponse
    {
        $scopes = [Gmail::MAIL_GOOGLE_COM]; // TODO: abstract this
        $client = $this->googleAuthenticationService->getClient(Auth::id(), $scopes);
        $googleAuthUrl = $client->createAuthUrl();

        return redirect()->away($googleAuthUrl);
    }

    /**
     * @param Request $request
     * @return Response|Application|ResponseFactory
     * @throws Exception
     */
    public function redirectPage(Request $request): Response|Application|ResponseFactory
    {
        $authorizationCode = $request->query->get("code");
        $scopes = [Gmail::MAIL_GOOGLE_COM]; // TODO: abstract this

        $accessToken = $this->googleAuthenticationService->getAccessTokenFromAuthCode($authorizationCode, 1, $scopes); // TODO: get authenticated user's ID
        $this->googleAuthenticationService->storeAccessToken($accessToken, 1); // TODO: get authenticated user's ID

        return response("Access token stored"); // TODO: return a redirect?
    }
}
