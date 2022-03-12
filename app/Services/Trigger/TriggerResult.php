<?php

namespace App\Services\Trigger;

class TriggerResult
{

    public function __construct(
        public bool   $triggered,
        public ?object $context = null,
    )
    {
    }
}
