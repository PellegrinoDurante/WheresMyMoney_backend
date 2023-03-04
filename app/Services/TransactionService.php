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

        $pipeline->partial($dataset);

        $this->machineLearningService->saveModel(self::ESTIMATOR_NAME, $pipeline);
    }
}
