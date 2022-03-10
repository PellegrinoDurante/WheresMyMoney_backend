<?php

namespace App\Jobs;

use App\Models\Charge;
use App\Services\ChargeDataProvider\ChargeDataProviderFactory;
use App\Models\RecurringExpense;
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
    public function __construct(private TriggerFactory $triggerFactory, private ChargeDataProviderFactory $chargeDataProviderFactory)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RecurringExpense $recurringExpense)
    {
        // TODO: handle exceptions

        // Check if trigger is triggered
        $triggerConfig = $recurringExpense->trigger; // TODO: change trigger from array to object
        $trigger = $this->triggerFactory->create((object)$triggerConfig);
        $triggerResult = $trigger->check();

        // Return if it is not triggered
        if (!$triggerResult->triggered) {
            return;
        }

        // Otherwise, continue getting the charge data with the provider
        $providerConfig = $recurringExpense->charge_data_provider; // TODO: change charge_data_provider from array to object
        $provider = $this->chargeDataProviderFactory->create((object)$providerConfig);
        $chargeData = $provider->getData($triggerResult->context);

        // Add a new draft charge
        Charge::create([
            "amount" => $chargeData->amount,
            "charged_at" => $chargeData->chargedAt,
            "draft" => true,
        ]);

        // TODO: send a notification for the new draft charge
    }
}
