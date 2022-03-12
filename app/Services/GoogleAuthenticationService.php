<?php

namespace App\Services;

use App\Models\AccessToken;
use App\Models\User;
use Google\Client;
use Google\Exception;
use Log;
use Storage;

class GoogleAuthenticationService
{

    /**
     * @param int $userId
     * @param array|string $scopes
     * @return Client
     * @throws Exception
     */
    public function getClient(int $userId, array|string $scopes = []): Client
    {
        $client = $this->createGoogleClient($scopes);

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

    /**
     * @param string $authorizationCode
     * @param array|string $scopes
     * @return array
     * @throws Exception
     */
    public function getAccessTokenFromAuthCode(string $authorizationCode, array|string $scopes = []): array
    {
        $client = $this->createGoogleClient($scopes);
        return $client->fetchAccessTokenWithAuthCode($authorizationCode);
    }

    public function storeAccessToken(array $accessToken, int $userId)
    {
        Log::info("Storing access token", $accessToken);

        AccessToken::updateOrCreate(
            ["user_id" => $userId],
            $accessToken
        );
    }

    /**
     * @param array|string $scopes
     * @return Client
     * @throws Exception
     */
    private function createGoogleClient(array|string $scopes = []): Client
    {
        // Create the Google client
        $client = new Client();
        $client->setApplicationName("Wheres_My_Money_backend");
        $client->setAuthConfig(Storage::path("client_secret.json"));
        $client->addScope($scopes);
        $client->setAccessType("offline");

        $redirectUri = route("auth.google.redirect", absolute: false);
        $client->setRedirectUri("http://localhost" . $redirectUri);

        $client->setTokenCallback(function ($cacheKey, $accessToken) {
            $this->storeAccessToken([
                "access_token" => $accessToken,
                "expires_in" => 3600,
                "created" => time(),
            ], 1);
        });

        return $client;
    }
}
