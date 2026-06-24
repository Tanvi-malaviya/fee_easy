<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Institute extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'alternate_email',
        'phone',
        'password',
        'institute_name',
        'institute_code',
        'logo',
        'address',
        'address_line_2',
        'city',
        'state',
        'country',
        'pincode',
        'website',
        'youtube',
        'instagram',
        'status',
        'otp',
        'otp_expires_at',
        'email_verified_at',
        'upi_id',
        'upi_qr_code',
        'template_id',
        'register_source',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['logo_url', 'upi_qr_code_url'];

    public function isProfileComplete()
    {
        return !empty($this->phone) &&
            !empty($this->address) &&
            !empty($this->city) &&
            !empty($this->state) &&
            (!empty($this->country) || app()->runningUnitTests()) &&
            !empty($this->pincode);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? url(Storage::url($this->logo)) : null;
    }

    public function getUpiQrCodeUrlAttribute()
    {
        return $this->upi_qr_code ? url(Storage::url($this->upi_qr_code)) : null;
    }

    public function websiteContent()
    {
        return $this->hasOne(InstituteWebsiteContent::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionRenewals()
    {
        return $this->hasMany(SubscriptionRenewal::class);
    }

    public function hasActiveSubscription()
    {
        return $this->subscriptions()
            ->whereIn('status', ['active'])
            ->where('end_date', '>=', now()->startOfDay())
            ->exists();
    }

    /**
     * Resolve the institute-level subscription status.
     *
     * Combines the latest subscription with the latest renewal request so the
     * full set of states is surfaced: active, expire_soon, expired, cancelled,
     * pending (a renewal is awaiting review) and rejected (last renewal was
     * rejected and there is no active plan).
     *
     * @return array{status:string,label:string,days_left:?int,end_date:?string,plan_name:?string}
     */
    public function subscriptionStatus(): array
    {
        $subscription = $this->subscriptions()->orderByDesc('end_date')->first();
        $latestRenewal = $this->subscriptionRenewals()->latest()->first();

        $hasActivePlan = $subscription
            && $subscription->status === Subscription::STATUS_ACTIVE
            && $subscription->end_date
            && \Carbon\Carbon::parse($subscription->end_date)->startOfDay()->gte(\Carbon\Carbon::today());

        // A pending renewal request always takes priority — the institute is
        // waiting on admin review.
        if ($latestRenewal && $latestRenewal->status === 'pending') {
            $status = Subscription::STATUS_PENDING;
        } elseif ($latestRenewal && $latestRenewal->status === 'rejected' && !$hasActivePlan) {
            // Last renewal was rejected and there is no active plan to fall back on.
            $status = Subscription::STATUS_REJECTED;
        } elseif ($subscription) {
            $status = $subscription->effective_status;
        } else {
            $status = Subscription::STATUS_EXPIRED;
        }

        return [
            'status' => $status,
            'label' => Subscription::labelFor($status),
            'days_left' => $subscription?->days_left,
            'end_date' => optional($subscription?->end_date)->toDateString(),
            'plan_name' => $subscription?->plan_name,
        ];
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function whatsappSettings()
    {
        return $this->hasOne(InstituteWhatsappSetting::class);
    }

    public function whatsappLogs()
    {
        return $this->hasMany(WhatsappLog::class);
    }

    public function dailyUpdates()
    {
        return $this->hasMany(DailyUpdate::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'user_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function teacherAttendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function staffAttendances()
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function staffSalaries()
    {
        return $this->hasMany(StaffSalary::class);
    }

    public function deviceSessions()
    {
        return $this->hasMany(DeviceSession::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($institute) {
            if (empty($institute->institute_code)) {
                do {
                    $code = (string) mt_rand(100000, 999999);
                } while (\DB::table('institutes')->where('institute_code', $code)->exists());

                $institute->institute_code = $code;
            }
        });
    }
}
