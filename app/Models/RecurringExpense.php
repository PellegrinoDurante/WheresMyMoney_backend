<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string name
 * @property string description
 * @property array trigger
 * @property array chargeDataProvider
 */
class RecurringExpense extends Model
{
    use HasFactory;

    protected $casts = [
        'trigger' => 'array',
        'chargeDataProvider' => 'array',
    ];

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }
}
