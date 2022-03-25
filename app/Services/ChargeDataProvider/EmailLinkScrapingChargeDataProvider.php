<?php

namespace App\Services\ChargeDataProvider;

use App\Facades\AmountParserFacade;
use App\Facades\ChargedAtParserFacade;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;

class EmailLinkScrapingChargeDataProvider implements ChargeDataProvider
{
    const TYPE = "email_link_scraping";

    public function __construct(
        private string  $linkXPath,
        private string  $amountXPath,
        private string  $chargedAtXPath,
        private string  $chargedAtFormat,
        private ?string $dateLocale = null,
        private ?string $clickBeforeXPath = null,
    )
    {
    }

    function getData(?object $context = null): ChargeData
    {
        // Scrap link from body HTML
        $emailCrawler = new Crawler($context->body);
        $link = $emailCrawler->filterXPath($this->linkXPath)->attr('href');

        // Create browser
        app('chrome')->start();
        $browser = app(Browser::class);

        // Go to link
        $browser->visit($link);

        // Optionally click before scrap data
        if ($this->clickBeforeXPath != null) {
            $browser->clickAtXPath($this->clickBeforeXPath);
        }

        // Scrap charge data from page
        $amountNode = $browser->driver->findElement(WebDriverBy::xpath($this->amountXPath));
        $chargedAtNode = $browser->driver->findElement(WebDriverBy::xpath($this->chargedAtXPath));

        // Parse and return charge data
        $amount = AmountParserFacade::parse($amountNode->getText());
        $chargedAt = ChargedAtParserFacade::parse($chargedAtNode->getText(), $this->chargedAtFormat, $this->dateLocale);

        $browser->quit();
        app('chrome')->stop();

        return new ChargeData($amount, $chargedAt);
    }
}
