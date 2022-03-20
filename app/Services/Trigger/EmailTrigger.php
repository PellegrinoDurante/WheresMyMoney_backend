<?php

namespace App\Services\Trigger;

use App\Services\GoogleAuthenticationService;
use Base64Url\Base64Url;
use Google\Exception;
use Google\Service\Gmail;
use Illuminate\Support\Facades\App;
use Log;

class EmailTrigger implements Trigger
{
    const TYPE = "email";

    private GoogleAuthenticationService $googleAuthenticationService;

    public function __construct(private int $userId, private string $subject)
    {
        $this->googleAuthenticationService = App::make(GoogleAuthenticationService::class);
    }

    public function check(): TriggerResult
    {
        try {
            $client = $this->googleAuthenticationService->getClient($this->userId, Gmail::MAIL_GOOGLE_COM); // TODO: userId
            $gmailService = new Gmail($client);

            $emails = $gmailService->users_messages->listUsersMessages('me', [
                'q' => 'subject:("' . $this->subject . '")',
                "maxResults" => 1
            ]);

            // Get last email's ID
            $lastEmailId = $emails->getMessages()[0]->getId();

            // Get last email content and attachments
            $lastEmail = $gmailService->users_messages->get('me', $lastEmailId);
            $lastEmailParts = collect($lastEmail->getPayload()->getParts());
            $pdfAttachmentParts = $lastEmailParts->filter(fn(Gmail\MessagePart $part) => $part->getMimeType() == 'application/pdf');

            $pdfAttachments = collect();
            $pdfAttachmentParts->each(function (Gmail\MessagePart $part) use ($lastEmailId, $gmailService, $pdfAttachments) {
                $pdfAttachmentId = $part->getBody()->attachmentId;
                $pdfAttachment = $gmailService->users_messages_attachments->get('me', $lastEmailId, $pdfAttachmentId)->data;
                $pdfAttachments->push((object)[
                    'name' => $part->filename,
                    'data' => Base64Url::decode($pdfAttachment),
                ]);
            });

            $context = (object)[
                'attachments' => $pdfAttachments,
            ];

            return new TriggerResult($lastEmailId, triggerRef: $lastEmailId, context: $context);

        } catch (Exception $e) {
            Log::error(__CLASS__ . '::' . __METHOD__ . ' - ' . $e->getMessage());
            return new TriggerResult(false);
        }
    }
}
