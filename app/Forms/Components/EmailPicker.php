<?php

namespace App\Forms\Components;

use App\Services\GoogleAuthenticationService;
use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Google\Service\Gmail;
use Illuminate\Support\Facades\Auth;

class EmailPicker extends Component
{
    protected string $view = 'forms.components.email-picker';

    public static function make(): static
    {
        return (new static())
            ->schema([
                TextInput::make('subject')
                    ->debounce()
                    ->reactive(),
                Radio::make('selected_email')
                    ->options(function (Closure $get, GoogleAuthenticationService $googleAuthenticationService) {
                        $client = $googleAuthenticationService->getClient(Auth::id(), Gmail::MAIL_GOOGLE_COM);
                        $gmail = new Gmail($client);

                        $emails = collect($gmail->users_messages->listUsersMessages('me', [
                            'q' => 'subject:' . $get('subject'),
                            "maxResults" => 5
                        ])->getMessages());

                        $emails = $emails->map(fn(Gmail\Message $message) => $gmail->users_messages->get('me', $message->getId()));
                        return $emails->mapWithKeys(fn(Gmail\Message $message) => [$message->getId() => collect($message->getPayload()->getHeaders())->first(fn(Gmail\MessagePartHeader $header) => $header->name == 'Subject')->value])->toArray();
                    })
                    ->reactive(),
            ]);
    }
}
