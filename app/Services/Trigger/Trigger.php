<?php

namespace App\Services\Trigger;

interface Trigger
{
    function check(): TriggerResult;
}
