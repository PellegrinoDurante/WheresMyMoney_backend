<?php

namespace App\Exceptions\Solutions;

use JetBrains\PhpStorm\ArrayShape;
use Spatie\Ignition\Contracts\Solution;
use Storage;

class GenerateGoogleClientSecretSolution implements Solution
{

    public function getSolutionTitle(): string
    {
        return 'Create and copy a new OAuth Client credential';
    }

    public function getSolutionDescription(): string
    {
        return 'Select the project (or create a new one) and create a new credential of type OAuth Client. Download the generated `client_secret.json` and copy it to `'
            . Storage::path('client_secret.json') . '`';
    }

    #[ArrayShape(['Google Console' => "string", 'Google documentation' => "string"])]
    public function getDocumentationLinks(): array
    {
        return [
            'Google Console' => 'https://console.cloud.google.com/apis/credentials',
            'Google documentation' => 'https://cloud.google.com/docs/authentication/end-user',
        ];
    }
}
