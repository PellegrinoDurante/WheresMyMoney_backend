<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\RecurringExpense
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string|null $description
 * @property array $trigger
 * @property mixed $chargeDataProvider
 * @property-read Collection|Charge[] $charges
 * @property-read int|null $charges_count
 * @method static Builder|RecurringExpense newModelQuery()
 * @method static Builder|RecurringExpense newQuery()
 * @method static Builder|RecurringExpense query()
 * @method static Builder|RecurringExpense whereChargeDataProvider($value)
 * @method static Builder|RecurringExpense whereCreatedAt($value)
 * @method static Builder|RecurringExpense whereDescription($value)
 * @method static Builder|RecurringExpense whereId($value)
 * @method static Builder|RecurringExpense whereName($value)
 * @method static Builder|RecurringExpense whereTrigger($value)
 * @method static Builder|RecurringExpense whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RecurringExpense extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'trigger', 'charge_data_provider'];

    protected $casts = [
        'trigger' => 'array',
        'charge_data_provider' => 'array',
    ];

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }
}
