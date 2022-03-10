<?php

namespace App\Services\Trigger;

use JetBrains\PhpStorm\Pure;

class TriggerFactory
{
    /**
     * Create a Trigger based on the given configuration.
     *
     * @param object $config
     * @return Trigger
     * @throws UnsupportedTriggerTypeException
     */
    public function create(object $config): Trigger
    {
        return match ($config->type) {
            TemporalTrigger::TYPE => $this->buildTemporalTrigger($config),
            EmailTrigger::TYPE => $this->buildEmailTrigger($config),
            default => throw new UnsupportedTriggerTypeException($config->type),
        };
    }

    /**
     * @param object $config
     * @return TemporalTrigger
     */
    #[Pure]
    private function buildTemporalTrigger(object $config): TemporalTrigger
    {
        return new TemporalTrigger();
    }

    /**
     * @param object $config
     * @return EmailTrigger
     */
    #[Pure]
    private function buildEmailTrigger(object $config): EmailTrigger
    {
        return new EmailTrigger();
    }
}
