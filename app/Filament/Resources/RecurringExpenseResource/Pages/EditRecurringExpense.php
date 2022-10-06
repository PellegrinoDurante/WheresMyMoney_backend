<?php

namespace App\Filament\Resources\RecurringExpenseResource\Pages;

use App\Filament\Resources\RecurringExpenseResource;
use App\Services\ChargeDataProvider\EmailAttachmentPdfChargeDataProvider;
use App\Services\ChargeDataProvider\EmailBodyScrapingChargeDataProvider;
use App\Services\ChargeDataProvider\EmailLinkScrapingChargeDataProvider;
use App\Services\ChargeDataProvider\UserDefinedChargeDataProvider;
use App\Services\Trigger\EmailTrigger;
use App\Services\Trigger\TemporalTrigger;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRecurringExpense extends EditRecord
{
    protected static string $resource = RecurringExpenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $trigger = [
            'type' => $data['trigger_type']
        ];

        switch ($data['trigger_type']) {
            case TemporalTrigger::TYPE:
                $trigger['cron'] = $data['trigger_user_defined_cron'];
                break;

            case EmailTrigger::TYPE:
                $trigger['subject'] = $data['trigger_email_subject'];
                break;
        }

        $chargeDataProvider = [
            'type' => $data['charge_data_provider_type'],
        ];

        switch ($data['charge_data_provider_type']) {
            case UserDefinedChargeDataProvider::TYPE:
                $chargeDataProvider['amount'] = $data['cdp_user_defined_value'];
                $chargeDataProvider['chargedAt'] = $data['cdp_user_defined_charged_at'];
                break;

            case EmailBodyScrapingChargeDataProvider::TYPE:
                $chargeDataProvider['amountXPath'] = $data['cdp_email_body_scraping_amount_xpath'];
                $chargeDataProvider['chargedAtXPath'] = $data['cdp_email_body_scraping_charged_at_xpath'];
                $chargeDataProvider['chargedAtFormat'] = $data['cdp_email_body_scraping_charged_at_format'];
                $chargeDataProvider['dateLocale'] = $data['cdp_email_body_scraping_date_locale'];
                break;

            case EmailAttachmentPdfChargeDataProvider::TYPE:
                $chargeDataProvider['index'] = $data['cdp_email_attachment_pdf_index'];
                $chargeDataProvider['page'] = $data['cdp_email_attachment_pdf_page'];
                $chargeDataProvider['amountPosX'] = $data['cdp_email_attachment_pdf_amount_pos_x'];
                $chargeDataProvider['amountPosY'] = $data['cdp_email_attachment_pdf_amount_pos_y'];
                $chargeDataProvider['amountWidth'] = $data['cdp_email_attachment_pdf_amount_width'];
                $chargeDataProvider['amountHeight'] = $data['cdp_email_attachment_pdf_amount_height'];
                $chargeDataProvider['chargedAtPosX'] = $data['cdp_email_attachment_pdf_charged_at_pos_x'];
                $chargeDataProvider['chargedAtPosY'] = $data['cdp_email_attachment_pdf_charged_at_pos_y'];
                $chargeDataProvider['chargedAtWidth'] = $data['cdp_email_attachment_pdf_charged_at_width'];
                $chargeDataProvider['chargedAtHeight'] = $data['cdp_email_attachment_pdf_charged_at_height'];
                $chargeDataProvider['dateFormat'] = $data['cdp_email_attachment_pdf_charged_at_format'];
                $chargeDataProvider['dateLocale'] = $data['cdp_email_attachment_pdf_date_locale'];
                break;

            case EmailLinkScrapingChargeDataProvider::TYPE:
                $chargeDataProvider['linkXPath'] = $data['cdp_email_link_scraping_link_xpath'];
                $chargeDataProvider['amountXPath'] = $data['cdp_email_link_scraping_amount_xpath'];
                $chargeDataProvider['chargedAtXPath'] = $data['cdp_email_link_scraping_charged_at_xpath'];
                $chargeDataProvider['chargedAtFormat'] = $data['cdp_email_link_scraping_charged_at_format'];
                $chargeDataProvider['dateLocale'] = $data['cdp_email_link_scraping_date_locale'];
                $chargeDataProvider['clickBeforeXPath'] = $data['cdp_email_link_scraping_click_before_xpath'];
                break;
        }

        $data['trigger'] = $trigger;
        $data['charge_data_provider'] = $chargeDataProvider;

        return parent::handleRecordUpdate($record, $data);
    }
}
