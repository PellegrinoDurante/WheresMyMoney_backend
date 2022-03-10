<?php

namespace App\Services\Trigger;

use Exception;
use JetBrains\PhpStorm\Pure;

class UnsupportedTriggerTypeException extends Exception
{

    #[Pure] public function __construct(string $type)
    {
        parent::__construct(sprintf("Unsupported trigger type: %s", $type));
    }
}
