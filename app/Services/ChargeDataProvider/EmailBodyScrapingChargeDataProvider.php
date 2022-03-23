<?php

namespace App\Services\ChargeDataProvider;

use App\Facades\AmountParserFacade;
use App\Facades\ChargedAtParserFacade;
use Symfony\Component\DomCrawler\Crawler;

class EmailBodyScrapingChargeDataProvider implements ChargeDataProvider
{
    const TYPE = "email_body";

    public function __construct(
        private string  $amountXPath,
        private string  $chargedAtXPath,
        private string  $chargedAtFormat,
        private ?string $dateLocale,
    )
    {
    }

    function getData(?object $context = null): ChargeData
    {
        // Scrap data from body HTML
        $crawler = new Crawler($context->body);
        $amountNode = $crawler->filterXPath($this->amountXPath);
        $chargedAtNode = $crawler->filterXPath($this->chargedAtXPath);

        // Parse and return charge data
        $amount = AmountParserFacade::parse($amountNode->text());
        $chargedAt = ChargedAtParserFacade::parse($chargedAtNode->text(), $this->chargedAtFormat, $this->dateLocale);
        return new ChargeData($amount, $chargedAt);
    }
}
