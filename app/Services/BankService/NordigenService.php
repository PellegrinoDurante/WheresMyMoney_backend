<?php

namespace App\Services\BankService;

use App\Models\AccessToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Nordigen\NordigenPHP\API\Account;
use Nordigen\NordigenPHP\API\NordigenClient;

class NordigenService implements BankServiceInterface
{

    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.nordigen.client_id');
        $this->clientSecret = config('services.nordigen.client_secret');
    }

    public function getBanksList(string $countryCode = 'IT'): Collection
    {
        return collect($this->client()->institution->getInstitutionsByCountry($countryCode));
    }

    public function getBalance(AccessToken $accessToken): float
    {
        return $this->account($accessToken)->getAccountBalances()['balances'][0]['balanceAmount']['amount'];
    }
    public function getTransactions(AccessToken $accessToken, Carbon $dateFrom, Carbon $dateTo): Collection
    {
        return collect($this->account($accessToken)->getAccountTransactions($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d')));
    }

    public function initSession(string $institutionId, string $redirect): array
    {
        return $this->client()->initSession($institutionId, $redirect);
    }

    private function client(): NordigenClient
    {
        $client = new NordigenClient($this->clientId, $this->clientSecret);

        // Check saved Nordigen access token
        $nordigenAccessToken = AccessToken::ofUser(\Auth::user())
            ->where('type', '=', AccessToken::TYPE_BANK)
            ->where('provider', '=', AccessToken::PROVIDER_NORDIGEN)
            ->first();

        // TODO: handle access token expiration
        // If access_tokens record is expired (see expires_in column) or the response code is 401 then it should call
        // the refresh token request, save new access_token and expires_in and retry the operation.
        // If the refresh request also fails with 401 it means that the refresh token is expired too; proceed deleting
        // access_tokens record and call getAccount method again to create a new access token from ground up.
        if ($nordigenAccessToken == null) {
            $accessToken = $client->createAccessToken();

            $nordigenAccessToken = AccessToken::create([
                'user_id' => \Auth::id(),
                'name' => 'Nordigen',
                'type' => AccessToken::TYPE_BANK,
                'provider' => AccessToken::PROVIDER_NORDIGEN,
                'access_token' => $accessToken['access'],
                'refresh_token' => $accessToken['refresh'],
                'expires_in' => $accessToken['access_expires'],
            ]);
        }

        $client->setAccessToken($nordigenAccessToken->access_token);
        $client->setRefreshToken($nordigenAccessToken->refresh_token);

        return $client;
    }

    private function account(AccessToken $accessToken): Account
    {
//        $bankAccessToken = AccessToken::ofUser(\Auth::user())
//            ->where('type', '=', AccessToken::TYPE_BANK)
//            ->where('provider', '=', AccessToken::PROVIDER_BANK)
//            ->first();
//
//        // TODO: handle bank access token / requisition ID expiration
//        if ($bankAccessToken == null) {
//            throw new UnauthorizedException(); // TODO: add custom exception
//        }

        $requisitionData = $this->client()->requisition->getRequisition($accessToken->access_token);
        $accountId = $requisitionData["accounts"][0];
        return $this->client()->account($accountId);
    }
}
