<?php

namespace App\Services\Trigger;

use JetBrains\PhpStorm\Pure;

class TriggerFactory
{
    /**
     * Create a Trigger based on the given configuration.
     *
     * @param object $config
     * @param int|null $userId
     * @return Trigger
     * @throws UnsupportedTriggerTypeException
     */
    public function create(object $config, ?int $userId = null): Trigger
    {
        return match ($config->type) {
            TemporalTrigger::TYPE => $this->buildTemporalTrigger(),
            EmailTrigger::TYPE => $this->buildEmailTrigger($userId, $config),
            default => throw new UnsupportedTriggerTypeException($config->type),
        };
    }

    /**
     * @return TemporalTrigger
     */
    #[Pure]
    private function buildTemporalTrigger(): TemporalTrigger
    {
        return new TemporalTrigger();
    }

    /**
     * @param object $config
     * @param int|null $userId
     * @return EmailTrigger
     */
    private function buildEmailTrigger(?int $userId, object $config): EmailTrigger
    {
        return new EmailTrigger($userId, $config->subject);
    }
}
