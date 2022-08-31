<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\AccessToken
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $access_token
 * @property string $refresh_token
 * @property int $expires_in
 * @property int $created
 * @method static Builder|AccessToken newModelQuery()
 * @method static Builder|AccessToken newQuery()
 * @method static Builder|AccessToken query()
 * @method static Builder|AccessToken whereAccessToken($value)
 * @method static Builder|AccessToken whereCreated($value)
 * @method static Builder|AccessToken whereCreatedAt($value)
 * @method static Builder|AccessToken whereExpiresIn($value)
 * @method static Builder|AccessToken whereId($value)
 * @method static Builder|AccessToken whereRefreshToken($value)
 * @method static Builder|AccessToken whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $user_id
 * @method static Builder|AccessToken whereUserId($value)
 * @property-read \App\Models\User $user
 * @mixin IdeHelperAccessToken
 */
class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id',
        'access_token',
        'refresh_token',
        'expires_in',
        'created',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
