<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Charge
 *
 * @mixin Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $recurring_expense_id
 * @property float $amount
 * @property string $charged_at
 * @property boolean $draft
 * @property-read RecurringExpense|null $recurringExpense
 * @method static Builder|Charge newModelQuery()
 * @method static Builder|Charge newQuery()
 * @method static Builder|Charge query()
 * @method static Builder|Charge whereId($value)
 * @method static Builder|Charge whereCreatedAt($value)
 * @method static Builder|Charge whereUpdatedAt($value)
 * @method static Builder|Charge whereRecurringExpenseId($value)
 * @method static Builder|Charge whereAmount($value)
 * @method static Builder|Charge whereChargedAt($value)
 * @method static Builder|Charge whereDraft($value)
 * @mixin IdeHelperCharge
 */
class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'recurring_expense_id',
        'amount',
        'charged_at',
        'draft',
    ];

    protected $casts = [
        'draft' => 'bool',
    ];

    protected static function booted()
    {
        static::addGlobalScope('belongs_to_logged_user', function (Builder $query) {
            $query->whereHas('recurringExpense', function (Builder $query) {
                $query->where('user_id', Auth::id());
            });
        });
    }

    public function recurringExpense(): BelongsTo
    {
        return $this->belongsTo(RecurringExpense::class);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100
        );
    }
}
