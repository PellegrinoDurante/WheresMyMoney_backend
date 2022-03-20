<?php

namespace App\Services\Trigger;

use App\Services\GoogleAuthenticationService;
use Google\Exception;
use Google\Service\Gmail;
use Illuminate\Support\Facades\App;
use Log;

class EmailTrigger implements Trigger
{
    const TYPE = "email";

    private GoogleAuthenticationService $googleAuthenticationService;

    public function __construct(private string $subject)
    {
        $this->googleAuthenticationService = App::make(GoogleAuthenticationService::class);
    }

    public function check(): TriggerResult
    {
        try {
            $client = $this->googleAuthenticationService->getClient(1, Gmail::MAIL_GOOGLE_COM); // TODO: userId
            $gmailService = new Gmail($client);

            $emails = $gmailService->users_messages->listUsersMessages('me', [
                'q' => 'subject:("' . $this->subject . '")',
                "maxResults" => 1
            ]);

            $lastEmail = $emails->getMessages()[0];
            $lastEmailId = $lastEmail->getId();

            return new TriggerResult($lastEmailId, triggerRef: $lastEmailId, context: $lastEmail);

        } catch (Exception $e) {
            Log::error(__CLASS__ . '::' . __METHOD__ . ' - ' . $e->getMessage());
            return new TriggerResult(false);
        }
    }
}
