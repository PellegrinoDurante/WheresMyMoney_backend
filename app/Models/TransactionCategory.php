<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\TransactionCategory
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $name
 * @method static Builder|TransactionCategory newModelQuery()
 * @method static Builder|TransactionCategory newQuery()
 * @method static Builder|TransactionCategory query()
 * @method static Builder|TransactionCategory whereCreatedAt($value)
 * @method static Builder|TransactionCategory whereDeletedAt($value)
 * @method static Builder|TransactionCategory whereId($value)
 * @method static Builder|TransactionCategory whereName($value)
 * @method static Builder|TransactionCategory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TransactionCategory extends Model
{
    protected $fillable = [
        'name'
    ];
}
