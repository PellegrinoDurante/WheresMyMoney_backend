<?php

namespace App\Exceptions\Solutions;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Throwable;

class MissingGoogleClientSecretSolutionProvider implements HasSolutionsForThrowable
{

    public function canSolve(Throwable $throwable): bool
    {
        if (!$throwable instanceof InvalidArgumentException) {
            return false;
        }

        return str_contains($throwable->getMessage(), 'client_secret.json') && str_ends_with($throwable->getMessage(), 'does not exist');
    }

    #[Pure]
    public function getSolutions(Throwable $throwable): array
    {
        return [
            new GenerateGoogleClientSecretSolution(),
        ];
    }
}
