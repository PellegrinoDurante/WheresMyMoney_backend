<?php

namespace App\Services\ChargeDataProvider;

use Carbon\Carbon;
use JetBrains\PhpStorm\Pure;

class ChargeDataProviderFactory
{
    /**
     * Create a ChargeDataProvider based on the given configuration.
     *
     * @param object $config
     * @return ChargeDataProvider
     * @throws UnsupportedChargeDataProviderTypeException
     */
    public function create(object $config): ChargeDataProvider
    {
        return match ($config->type) {
            UserDefinedChargeDataProvider::TYPE => $this->buildUserDefinedChargeDataProvider($config),
            EmailAttachmentPdfChargeDataProvider::TYPE => $this->buildEmailAttachmentPdfChargeDataProvider($config),
            EmailLinkScrapingChargeDataProvider::TYPE => $this->buildEmailLinkScrapingChargeDataProvider($config),
            default => throw new UnsupportedChargeDataProviderTypeException($config->type),
        };
    }

    /**
     * @param object $config
     * @return UserDefinedChargeDataProvider
     */
    private function buildUserDefinedChargeDataProvider(object $config): UserDefinedChargeDataProvider
    {
        $chargedAt = !empty($config->chargedAt) ? new Carbon($config->chargedAt) : null;
        return new UserDefinedChargeDataProvider($config->amount, $chargedAt);
    }

    /**
     * @param object $config
     * @return EmailAttachmentPdfChargeDataProvider
     */
    #[Pure]
    private function buildEmailAttachmentPdfChargeDataProvider(object $config): EmailAttachmentPdfChargeDataProvider
    {
        return new EmailAttachmentPdfChargeDataProvider();
    }

    /**
     * @param object $config
     * @return EmailLinkScrapingChargeDataProvider
     */
    #[Pure]
    private function buildEmailLinkScrapingChargeDataProvider(object $config): EmailLinkScrapingChargeDataProvider
    {
        return new EmailLinkScrapingChargeDataProvider();
    }
}
