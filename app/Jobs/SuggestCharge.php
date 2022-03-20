<?php

namespace App\Jobs;

use App\Models\RecurringExpense;
use App\Services\ChargeDataProvider\ChargeDataProviderFactory;
use App\Services\Trigger\TriggerFactory;
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
        $triggerConfig = $this->recurringExpense->trigger;
        $trigger = $triggerFactory->create($triggerConfig);
        $triggerResult = $trigger->check();

        // Return if it is not triggered or current trigger ref is the same as the last one executed
        if (!$triggerResult->triggered || $triggerResult?->triggerRef == $this->recurringExpense->last_trigger_ref) {
            return;
        }

        // Otherwise, continue getting the charge data with the provider
        $providerConfig = $this->recurringExpense->charge_data_provider;
        $provider = $chargeDataProviderFactory->create($providerConfig);
        $chargeData = $provider->getData($triggerResult->context);

        // Add a new draft charge
        $this->recurringExpense->charges()->create([
            "amount" => intval($chargeData->amount * 100),
            "charged_at" => $chargeData->chargedAt,
            "draft" => true,
        ]);

        // Save ref of this trigger to avoid duplicates
        $this->recurringExpense->last_trigger_ref = $triggerResult?->triggerRef;
        $this->recurringExpense->save();

        // TODO: send a notification for the new draft charge
    }
}
