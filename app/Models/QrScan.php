<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class QrScan extends Model
{
    protected $fillable = [
        'qr_type',
        'scanned_at',
        'ip_address',
        'browser',
        'os',
        'device_type',
        'user_agent',
        'referrer',
        'country',
        'city',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'latitude'   => 'float',
        'longitude'  => 'float',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('qr_type', $type);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('scanned_at', today());
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('scanned_at', now()->month)
                     ->whereYear('scanned_at', now()->year);
    }

    public function scopeThisYear(Builder $query): Builder
    {
        return $query->whereYear('scanned_at', now()->year);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getQrTypeLabelAttribute(): string
    {
        return match ($this->qr_type) {
            'web'     => 'Website',
            'android' => 'Android',
            'ios'     => 'iOS',
            default   => ucfirst($this->qr_type),
        };
    }

    public function getQrTypeBadgeColorAttribute(): string
    {
        return match ($this->qr_type) {
            'web'     => 'bg-blue-100 text-blue-700',
            'android' => 'bg-green-100 text-green-700',
            'ios'     => 'bg-gray-100 text-gray-700',
            default   => 'bg-gray-100 text-gray-600',
        };
    }

    public function hasGps(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
