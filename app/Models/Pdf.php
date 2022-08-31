<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Pdf
 *
 * @mixin \Barryvdh\LaravelIdeHelper\Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property string $path
 * @property-read User $user
 * @method static Builder|Pdf newModelQuery()
 * @method static Builder|Pdf newQuery()
 * @method static Builder|Pdf query()
 * @mixin Eloquent
 * @mixin IdeHelperPdf
 */
class Pdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
