<?php

namespace App\Http\Controllers;

use App\Services\GoogleAuthenticationService;
use Auth;
use Exception;
use Google\Exception as GoogleException;
use Google\Service\Gmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoogleAuthenticationController extends Controller
{
    public function __construct(private GoogleAuthenticationService $googleAuthenticationService)
    {
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function loginPage(Request $request): RedirectResponse
    {
        $successUrl = $request->get('redirect_url_success');
        $errorUrl = $request->get('redirect_url_error');
        $scopes = [Gmail::MAIL_GOOGLE_COM]; // TODO: abstract this

        try {
            $googleAuthUrl = $this->googleAuthenticationService->createAuthUrl(Auth::id(), $successUrl, $errorUrl, $scopes);
            return redirect()->away($googleAuthUrl);

        } catch (GoogleException|Exception $e) {
            return redirect()->away($errorUrl);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function redirectPage(Request $request): RedirectResponse
    {
        $redirectUrl = $this->googleAuthenticationService->getAccessTokenFromAuthCode($request, Auth::id());
        return redirect()->away($redirectUrl);
    }
}
