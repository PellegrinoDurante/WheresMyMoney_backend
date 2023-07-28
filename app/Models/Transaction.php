<?php

namespace App\Models;

use Akaunting\Money\Money;
use App\Casts\MoneyCast;
use App\Events\TransactionCreated;
use App\Events\TransactionUpdating;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property Money $amount
 * @property string $spent_at
 * @property array $metadata
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|Transaction onlyTrashed()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereAmount($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereDeletedAt($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereMetadata($value)
 * @method static Builder|Transaction whereSpentAt($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Transaction withoutTrashed()
 * @mixin Eloquent
 * @property int $wallet_id
 * @method static Builder|Transaction whereWalletId($value)
 * @property-read Wallet $wallet
 * @property int $category_id
 * @property-read TransactionCategory $category
 * @method static Builder|Transaction whereCategoryId($value)
 * @property int|null $guessed_category_id
 * @method static Builder|Transaction whereGuessedCategoryId($value)
 * @property-read \App\Models\TransactionCategory|null $guessedCategory
 */
class Transaction extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'spent_at' => 'datetime',
        'metadata' => 'array',
        'amount' => MoneyCast::class,
    ];

    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
        'updating' => TransactionUpdating::class,
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function guessedCategory(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'guessed_category_id');
    }

    public function scopeNegative(Builder $query): void
    {
        $query->where('amount', '<', 0);
    }

    public function scopeInCategories(Builder $query, array $categories = []): void
    {
        $query->when(!empty($categories), function (Builder $query) use ($categories) {
            $query->whereIn('category_id', $categories)
                ->orWhere(fn(Builder $query) => $query->whereNull('category_id')->whereIn('guessed_category_id', $categories));
        });
    }

    public function scopeNotInCategory(Builder $query, int $categoryId): void
    {
        $query->where(function (Builder $query) use ($categoryId) {
            $query
                ->where('category_id', '!=', $categoryId)
                ->orWhere(fn(Builder $query) => $query->whereNull('category_id')->where('guessed_category_id', '!=', $categoryId));
        });
    }
}
