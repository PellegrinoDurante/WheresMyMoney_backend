<?php

namespace App\Providers;

use App\Services\BankService\NordigenService;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class NordigenProvider extends AbstractProvider
{
    private NordigenService $nordigenService;

    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl, $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);
        $this->nordigenService = app(NordigenService::class);
    }

    protected function getAuthUrl($state)
    {
        $session = $this->nordigenService->initSession($this->parameters['institutionId'], route('auth.callback', [
            'driver' => 'nordigen',
            'name' => $this->parameters['name'],
            'state' => $state,
        ]));

        return $session['link'];
    }

    protected function getCode()
    {
        return $this->request->input('ref');
    }

    public function getAccessTokenResponse($code): array
    {
        $requisitionData = $this->nordigenService->getRequisition($code);
        $accountId = $requisitionData["accounts"][0];

        return [
            'access_token' => $accountId,
            'refresh_token' => '',
            'expires_in' => 90 * 24 * 60 * 60,
        ];
    }

    protected function getTokenUrl(): string
    {
        return 'Not used. See overridden getAccessTokenResponse!';
    }

    protected function getUserByToken($token): array
    {
        return [];
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user);
    }
}
