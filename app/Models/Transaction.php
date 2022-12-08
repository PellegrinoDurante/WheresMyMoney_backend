<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property int $amount
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
 */
class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'spent_at',
        'wallet_id',
        'metadata',
        'category_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100
        );
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }
}
