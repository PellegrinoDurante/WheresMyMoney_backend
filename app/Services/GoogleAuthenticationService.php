<?php

namespace App\Services;

use App\Models\AccessToken;
use App\Models\User;
use Exception;
use Google\Client;
use Google\Exception as GoogleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Log;
use Storage;

class GoogleAuthenticationService
{

    /**
     * @param int $userId
     * @param string $successUrl
     * @param string $errorUrl
     * @param array|string $scopes
     * @return string
     * @throws GoogleException
     * @throws Exception
     */
    public function createAuthUrl(int $userId, string $successUrl, string $errorUrl, array|string $scopes = []): string
    {
        // Create a state token to prevent request forgery.
        // Store it in the session for later validation.
        $securityToken = bin2hex(random_bytes(128 / 8));
        session(['security_token' => $securityToken]);

        // Create state object with state token and original url
        $state = Arr::query([
            'security_token' => $securityToken,
            'success_url' => $successUrl,
            'error_url' => $errorUrl,
        ]);

        // Create client and auth url
        $client = $this->getClient($userId, $scopes);
        $client->setState($state);
        return $client->createAuthUrl($scopes);
    }

    /**
     * Fetch access token from the authorization code, scopes and state in the given request.
     * Returns the success or the error url to redirect to, based on the operation result.
     *
     * @param Request $request
     * @param int $userId
     * @return string
     */
    public function getAccessTokenFromAuthCode(Request $request, int $userId): string
    {
        // Retrieve data from request
        $authorizationCode = $request->get('code');
        $scopes = $request->get('scope');
        parse_str($request->get('state'), $state);

        try {
            $this->checkSecurityToken($state['security_token']);

            $client = $this->createGoogleClient($userId, $scopes);
            $accessToken = $client->fetchAccessTokenWithAuthCode($authorizationCode);
            $this->storeAccessToken($accessToken, $userId); // TODO: check is this call is useless; see setTokenCallback below

            return $state['success_url'];

        } catch (AuthorizationException|GoogleException $e) {
            return $state['error_url'];
        }
    }

    /**
     * @param int $userId
     * @param array|string $scopes
     * @return Client
     * @throws GoogleException
     */
    public function getClient(int $userId, array|string $scopes = []): Client
    {
        $client = $this->createGoogleClient($userId, $scopes);

        $storedAccessToken = User::find($userId)->accessToken;

        if ($storedAccessToken != null) {
            $client->setAccessToken([
                "access_token" => $storedAccessToken->access_token,
                "refresh_token" => $storedAccessToken->refresh_token,
                "expires_in" => $storedAccessToken->expires_in,
                "created" => $storedAccessToken->created,
            ]);
        }

        return $client;
    }

    private function storeAccessToken(array $accessToken, int $userId)
    {
        Log::info("Storing access token", $accessToken);

        AccessToken::updateOrCreate(
            ["user_id" => $userId],
            $accessToken
        );
    }

    /**
     * @param string $securityToken
     * @return void
     * @throws AuthorizationException
     */
    private function checkSecurityToken(string $securityToken)
    {
        if ($securityToken !== session('security_token')) {
            throw new AuthorizationException('Invalid security token');
        }
    }

    /**
     * @param int $userId
     * @param array|string $scopes
     * @return Client
     * @throws GoogleException
     */
    private function createGoogleClient(int $userId, array|string $scopes = []): Client
    {
        // Create the Google client
        $client = new Client();
        $client->setApplicationName("Wheres_My_Money_backend");
        $client->setAuthConfig(Storage::path("client_secret.json"));
        $client->addScope($scopes);
        $client->setAccessType("offline");

        $redirectUri = route("auth.google.redirect", absolute: false);
        $client->setRedirectUri(env('APP_URL') . $redirectUri);

        $client->setTokenCallback(function ($cacheKey, $accessToken) use ($userId) {
            $this->storeAccessToken([
                "access_token" => $accessToken,
                "expires_in" => 3600,
                "created" => time(),
            ], $userId);
        });

        return $client;
    }
}
