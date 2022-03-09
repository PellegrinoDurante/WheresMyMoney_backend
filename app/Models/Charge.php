<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Charge
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $recurring_expense_id
 * @property float $amount
 * @method static Builder|Charge newModelQuery()
 * @method static Builder|Charge newQuery()
 * @method static Builder|Charge query()
 * @method static Builder|Charge whereAmount($value)
 * @method static Builder|Charge whereCreatedAt($value)
 * @method static Builder|Charge whereId($value)
 * @method static Builder|Charge whereRecurringExpenseId($value)
 * @method static Builder|Charge whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Charge extends Model
{
    use HasFactory;

    protected $fillable = ['amount'];
}
