<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * Subscription / renewal status values.
     *
     * NOTE: `expire_soon` is a *derived* status only — it is never written to
     * the database. Persisting it would break hasActiveSubscription() and the
     * subscription middleware, which treat anything other than 'active' as no
     * access. It is computed on the fly from end_date.
     */
    public const STATUS_ACTIVE      = 'active';
    public const STATUS_EXPIRE_SOON = 'expire_soon';
    public const STATUS_EXPIRED     = 'expired';
    public const STATUS_CANCELLED   = 'cancelled';
    public const STATUS_PENDING     = 'pending';
    public const STATUS_REJECTED    = 'rejected';

    /**
     * Number of days before end_date at which an active subscription is
     * considered "expiring soon". Shared by the API responses, the dashboard
     * and the daily expiry checker so the threshold stays consistent.
     */
    public const EXPIRE_SOON_THRESHOLD_DAYS = 7;

    protected $fillable = [
        'institute_id',
        'plan_name',
        'amount',
        'start_date',
        'end_date',
        'status',
        'apple_transaction_id',
        'google_order_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Expose the derived status fields on every serialized subscription
     * (show, history, etc.) so clients always receive them.
     */
    protected $appends = [
        'effective_status',
        'status_label',
        'days_left',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class)->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    /**
     * Human-readable labels for each status value.
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_ACTIVE      => 'Active',
            self::STATUS_EXPIRE_SOON => 'Expire Soon',
            self::STATUS_EXPIRED     => 'Expired',
            self::STATUS_CANCELLED   => 'Cancel',
            self::STATUS_PENDING     => 'Pending',
            self::STATUS_REJECTED    => 'Reject',
        ];
    }

    public static function labelFor(?string $status): string
    {
        return self::statusLabels()[$status] ?? ucfirst((string) $status);
    }

    /**
     * Whole days remaining until end_date (0 once it has passed, null when
     * there is no end_date).
     */
    public function getDaysLeftAttribute(): ?int
    {
        if (! $this->end_date) {
            return null;
        }

        $days = Carbon::today()->diffInDays(
            Carbon::parse($this->end_date)->startOfDay(),
            false
        );

        return $days < 0 ? 0 : (int) $days;
    }

    /**
     * The effective status of this subscription, factoring in the end date.
     *
     * - cancelled / pending / rejected: returned as stored (explicit states).
     * - end_date in the past:           expired.
     * - end_date within the threshold:  expire_soon.
     * - otherwise:                       active.
     */
    public function getEffectiveStatusAttribute(): string
    {
        // Explicit states stored on the row take precedence over dates.
        if (in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_PENDING, self::STATUS_REJECTED], true)) {
            return $this->status;
        }

        if (! $this->end_date) {
            return $this->status ?: self::STATUS_ACTIVE;
        }

        $today = Carbon::today();
        $end = Carbon::parse($this->end_date)->startOfDay();

        if ($end->lt($today)) {
            return self::STATUS_EXPIRED;
        }

        if ($end->diffInDays($today) <= self::EXPIRE_SOON_THRESHOLD_DAYS) {
            return self::STATUS_EXPIRE_SOON;
        }

        return self::STATUS_ACTIVE;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::labelFor($this->effective_status);
    }
}
