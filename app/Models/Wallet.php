<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Wallet
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $name
 * @property int|null $access_token_id
 * @property-read AccessToken|null $accessToken
 * @method static Builder|Wallet newModelQuery()
 * @method static Builder|Wallet newQuery()
 * @method static Builder|Wallet query()
 * @method static Builder|Wallet whereAccessTokenId($value)
 * @method static Builder|Wallet whereCreatedAt($value)
 * @method static Builder|Wallet whereDeletedAt($value)
 * @method static Builder|Wallet whereId($value)
 * @method static Builder|Wallet whereName($value)
 * @method static Builder|Wallet whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Wallet extends Model
{
    protected $fillable = [
        'name',
        'access_token_id',
    ];

    public function accessToken(): BelongsTo
    {
        return $this->belongsTo(AccessToken::class);
    }
}
