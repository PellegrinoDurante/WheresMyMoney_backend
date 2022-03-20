<?php

namespace App\Jobs;

use App\Models\RecurringExpense;
use App\Services\ChargeDataProvider\ChargeData;
use App\Services\ChargeDataProvider\ChargeDataProviderFactory;
use App\Services\ChargeDataProvider\UnableToGetChargeDataException;
use App\Services\ChargeDataProvider\UnsupportedChargeDataProviderTypeException;
use App\Services\Trigger\TriggerFactory;
use App\Services\Trigger\TriggerResult;
use App\Services\Trigger\UnsupportedTriggerTypeException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SuggestCharge implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private RecurringExpense $recurringExpense)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TriggerFactory $triggerFactory, ChargeDataProviderFactory $chargeDataProviderFactory)
    {
        // TODO: handle exceptions

        // Check if trigger is triggered
        $triggerResult = $this->getTriggerResult($triggerFactory);

        // Return if it is not triggered or current trigger ref is the same as the last one executed
        if (!$this->canCreateDraftCharge($triggerResult)) {
            return;
        }

        // Otherwise, continue getting the charge data with the provider
        $chargeData = $this->getChargeData($chargeDataProviderFactory, $triggerResult);

        // Add a new draft charge
        $this->createDraftCharge($chargeData);

        // Save ref of this trigger to avoid duplicates
        $this->saveLastTriggerReference($triggerResult);

        // TODO: send a notification for the new draft charge
        $this->sendNotification();
    }

    /**
     * @param TriggerFactory $triggerFactory
     * @return TriggerResult
     * @throws UnsupportedTriggerTypeException
     */
    private function getTriggerResult(TriggerFactory $triggerFactory): TriggerResult
    {
        $triggerConfig = $this->recurringExpense->trigger;
        $trigger = $triggerFactory->create($triggerConfig, $this->recurringExpense->user_id);
        return $trigger->check();
    }

    /**
     * @param TriggerResult $triggerResult
     * @return bool
     */
    private function canCreateDraftCharge(TriggerResult $triggerResult): bool
    {
        if (!$triggerResult->triggered) {
            return false;
        }

        if ($triggerResult?->triggerRef != null && $triggerResult?->triggerRef == $this->recurringExpense->last_trigger_ref) {
            return false;
        }

        return true;
    }

    /**
     * @param ChargeData $chargeData
     */
    private function createDraftCharge(ChargeData $chargeData): void
    {
        $this->recurringExpense->charges()->create([
            "amount" => intval($chargeData->amount * 100),
            "charged_at" => $chargeData->chargedAt,
            "draft" => true,
        ]);
    }

    /**
     * @param ChargeDataProviderFactory $chargeDataProviderFactory
     * @param TriggerResult $triggerResult
     * @return ChargeData
     * @throws UnsupportedChargeDataProviderTypeException
     * @throws UnableToGetChargeDataException
     */
    protected function getChargeData(ChargeDataProviderFactory $chargeDataProviderFactory, TriggerResult $triggerResult): ChargeData
    {
        $providerConfig = $this->recurringExpense->charge_data_provider;
        $provider = $chargeDataProviderFactory->create($providerConfig);
        return $provider->getData($triggerResult->context);
    }

    /**
     * @param TriggerResult $triggerResult
     */
    protected function saveLastTriggerReference(TriggerResult $triggerResult): void
    {
        $this->recurringExpense->last_trigger_ref = $triggerResult?->triggerRef;
        $this->recurringExpense->save();
    }

    private function sendNotification()
    {
        // TODO: implement this.
    }
}
