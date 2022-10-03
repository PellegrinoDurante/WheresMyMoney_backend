<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\RecurringExpense
 *
 * @mixin Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property object $trigger
 * @property object $charge_data_provider
 * @property-read Collection|Charge[] $charges
 * @property-read int|null $charges_count
 * @property string $last_trigger_ref
 * @method static Builder|RecurringExpense newModelQuery()
 * @method static Builder|RecurringExpense newQuery()
 * @method static Builder|RecurringExpense query()
 * @method static Builder|RecurringExpense whereId($value)
 * @method static Builder|RecurringExpense whereCreatedAt($value)
 * @method static Builder|RecurringExpense whereUpdatedAt($value)
 * @method static Builder|RecurringExpense whereUserId($value)
 * @method static Builder|RecurringExpense whereName($value)
 * @method static Builder|RecurringExpense whereDescription($value)
 * @method static Builder|RecurringExpense whereTrigger($value)
 * @method static Builder|RecurringExpense whereChargeDataProvider($value)
 * @method static Builder|RecurringExpense whereLastTriggerRef($value)
 * @mixin IdeHelperRecurringExpense
 */
class RecurringExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger',
        'charge_data_provider',
        'last_trigger_ref',
    ];

    protected $casts = [
        'trigger' => 'object',
        'charge_data_provider' => 'object',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OwnedByUserScope());
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }
}
