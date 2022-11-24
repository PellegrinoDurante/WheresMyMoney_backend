<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property-read User $user
 * @mixin IdeHelperAccessToken
 * @property string $name
 * @property string $type
 * @property string $provider
 * @method static Builder|AccessToken user(\App\Models\User $user)
 * @method static Builder|AccessToken whereName($value)
 * @method static Builder|AccessToken whereProvider($value)
 * @method static Builder|AccessToken whereType($value)
 * @method static Builder|AccessToken ofUser(\App\Models\User $user)
 */
class AccessToken extends Model
{
    use SoftDeletes;

    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_NORDIGEN = 'nordigen';
    const PROVIDER_BANK = 'bank';

    const TYPE_BANK = 'bank';

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'provider',
        'access_token',
        'refresh_token',
        'expires_in',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfUser(Builder $query, User $user)
    {
        $query->where('user_id', '=', $user->id);
    }
}
