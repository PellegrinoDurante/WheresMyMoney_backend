<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Support\Collection;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\WordCountVectorizer;

class TransactionService
{
    const ESTIMATOR_NAME = 'transaction_categories';

    public function __construct(private readonly MachineLearningService $machineLearningService)
    {
    }

    public function guessCategory(Transaction $transaction): TransactionCategory|null
    {
        $dataset = new Unlabeled([$transaction->metadata['remittanceInformation']]);

        /** @var Pipeline $pipeline */
        $pipeline = $this->machineLearningService->loadModel(self::ESTIMATOR_NAME);

        $guessedCategory = $pipeline->predict($dataset);

        return TransactionCategory::find($guessedCategory[0]);
    }

    public function relearnCategorization(Collection $transactions): void
    {
        $samples = $transactions->map(function (Transaction $transaction) {
            return [$transaction->metadata['remittanceInformation']];
        })->all();
        $labels = $transactions->pluck('category_id')->all();

        $dataset = new Labeled($samples, $labels);
        $dataset->transformLabels(strval(...));

        $pipeline = new Pipeline([
            new WordCountVectorizer(),
            new TfIdfTransformer(),
        ], new GaussianNB());

        $pipeline->train($dataset);

        $this->machineLearningService->saveModel(self::ESTIMATOR_NAME, $pipeline);
    }

    public function learnCategorization(Transaction $transaction): void
    {
        /** @var Pipeline $pipeline */
        $pipeline = $this->machineLearningService->loadModel(self::ESTIMATOR_NAME);

        $sample = $transaction->metadata['remittanceInformation'];
        $label = $transaction->category_id;

        $dataset = new Labeled([$sample], [$label]);
        $dataset->transformLabels(strval(...));

        $pipeline->partial($dataset);

        $this->machineLearningService->saveModel(self::ESTIMATOR_NAME, $pipeline);
    }

    public function checkDuplication(): void
    {
        Transaction::where('duplication_checked', '=', false)->get()
            ->each(function (Transaction $transaction) {
                if ($this->getPotentialDuplicates($transaction)->isEmpty()) {
                    $transaction->update(['duplication_checked' => true]);
                }
            });
    }

    public function detectDuplicates(): Collection
    {
        return Transaction::selectRaw('GROUP_CONCAT(id) AS ids, COUNT(*) AS c')
            ->groupBy('amount', 'spent_at', 'metadata->remittanceInformation')
            ->having('c', '>', 1)
            ->get()
            ->map(fn($duplicates) => explode(',', $duplicates['ids']));
    }

    public function getPotentialDuplicates(Transaction $transaction): Collection
    {
        return Transaction::where('amount', $transaction->amount)
            ->where('spent_at', $transaction->spent_at)
            ->where('metadata->remittanceInformation', $transaction->metadata['remittanceInformation'])
            ->get();
    }
}
