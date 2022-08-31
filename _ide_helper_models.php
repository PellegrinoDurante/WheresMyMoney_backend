<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AccessToken
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $access_token
 * @property string $refresh_token
 * @property int $expires_in
 * @property int $created
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccessToken whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperAccessToken {}
}

namespace App\Models{
/**
 * App\Models\Charge
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $recurring_expense_id
 * @property int $amount
 * @property string $charged_at
 * @property bool $draft
 * @property-read \App\Models\RecurringExpense $recurringExpense
 * @method static \Illuminate\Database\Eloquent\Builder|Charge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge query()
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereChargedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereRecurringExpenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Charge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCharge {}
}

namespace App\Models{
/**
 * App\Models\Pdf
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $path
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PdfFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pdf whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperPdf {}
}

namespace App\Models{
/**
 * App\Models\RecurringExpense
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $name
 * @property string|null $description
 * @property object $trigger
 * @property object $charge_data_provider
 * @property string|null $last_trigger_ref
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Charge[] $charges
 * @property-read int|null $charges_count
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereChargeDataProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereLastTriggerRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereTrigger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecurringExpense whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperRecurringExpense {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccessToken|null $accessToken
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pdf[] $pdfs
 * @property-read int|null $pdfs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RecurringExpense[] $recurringExpenses
 * @property-read int|null $recurring_expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

