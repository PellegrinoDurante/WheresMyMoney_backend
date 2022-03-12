<?php

namespace App\Services\Trigger;

use JetBrains\PhpStorm\Pure;

class TemporalTrigger implements Trigger
{
    const TYPE = "temporal";

    #[Pure]
    function check(): TriggerResult
    {
        // This always triggers because the trigger's check is scheduled with a cron-expression
        return new TriggerResult(true);
    }
}
