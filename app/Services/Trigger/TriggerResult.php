<?php

namespace App\Services\Trigger;

class TriggerResult
{

    public function __construct(
        public bool   $triggered,
        public ?string $triggerRef = null,
        public ?object $context = null,
    )
    {
    }
}
