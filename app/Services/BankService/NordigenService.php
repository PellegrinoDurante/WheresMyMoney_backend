<?php

namespace App\Services\BankService;

use App\Models\AccessToken;
use Closure;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\UnauthorizedException;
use Log;
use Nordigen\NordigenPHP\API\Account;
use Nordigen\NordigenPHP\API\NordigenClient;

class NordigenService extends BankServiceAbstract
{

    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.nordigen.client_id');
        $this->clientSecret = config('services.nordigen.client_secret');
    }

    public function getInstitutions(string $countryCode = 'IT'): Collection
    {
        return $this->handleExpiration(function () use ($countryCode) {
            return collect($this->client()->institution->getInstitutionsByCountry($countryCode));
        });
    }

    public function getBalance(AccessToken $accountAccessToken): float
    {
        return $this->handleExpiration(function () use ($accountAccessToken) {
            return $this->account($accountAccessToken)->getAccountBalances()['balances'][0]['balanceAmount']['amount'];
        });
    }

    public function getTransactions(AccessToken $accountAccessToken, Carbon $dateFrom, Carbon $dateTo): Collection
    {
        return $this->handleExpiration(function () use ($dateTo, $dateFrom, $accountAccessToken) {
            $accountTransactions = $this->account($accountAccessToken)->getAccountTransactions($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'));
            return collect($accountTransactions['transactions']['booked']);
        });
    }

    public function getMinDateAllowed(AccessToken $accountAccessToken): Carbon
    {
        // Nordigen allows access to transactions history up to 730 days
        return now()->subDays(730);
    }

    public function getRequisition(string $requisitionId): array
    {
        return $this->handleExpiration(function () use ($requisitionId) {
            return $this->client()->requisition->getRequisition($requisitionId);
        });
    }

    public function initSession(string $institutionId, string $redirect): array
    {
        return $this->handleExpiration(function () use ($institutionId, $redirect) {
            return $this->client()->initSession($institutionId, $redirect);
        });
    }

    /**
     * Return the Nordigen client using saved access token.
     * If no access token exists then a new one is created.
     * If the existing access token is expired gets a new one using the refresh token.
     * @return NordigenClient
     */
    private function client(): NordigenClient
    {
        $client = new NordigenClient($this->clientId, $this->clientSecret);

        // Check saved Nordigen access token
        $nordigenAccessToken = AccessToken::ofUser(\Auth::user())
            ->where('type', '=', AccessToken::TYPE_BANK)
            ->where('provider', '=', AccessToken::PROVIDER_NORDIGEN)
            ->first();

        // Create client access token id it does not exist
        if ($nordigenAccessToken == null) {
            Log::info('Nordigen access token not found, creating a new one...');
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

        // Refresh access token if expired
        if ($this->isTokenExpired($nordigenAccessToken)) {
            Log::info('Nordigen access token is expired, refreshing...');
            $accessToken = $client->refreshAccessToken($nordigenAccessToken->refresh_token);
            $nordigenAccessToken->access_token = $accessToken['access'];
            $nordigenAccessToken->expires_in = $accessToken['access_expires'];
            $nordigenAccessToken->expired_at = null;
            $nordigenAccessToken->save();
        }

        $client->setAccessToken($nordigenAccessToken->access_token);
        $client->setRefreshToken($nordigenAccessToken->refresh_token);
        return $client;
    }

    /**
     * Return the bank account associated with the given access token.
     * @param AccessToken $accessToken
     * @return Account
     */
    private function account(AccessToken $accessToken): Account
    {
        $account = $this->client()->account($accessToken->access_token);

        // Check account status
        $metadata = $account->getAccountMetaData();
        if ($metadata['status'] !== 'READY') {
            // TODO: account expired
            throw new UnauthorizedException();
        }

        return $account;
    }

    private function handleExpiration(Closure $closure, ...$args)
    {
        try {
            return $closure(...$args);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() !== 401) {
                throw $e;
            }

            // Mark client's access token as expired and retry the operation
            $accessToken = $this->client()->getAccessToken();
            $this->expireAccessToken($accessToken);
            return $closure(...$args);
        }
    }
}
