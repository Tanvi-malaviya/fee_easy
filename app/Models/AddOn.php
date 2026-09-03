<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory;

    public const KIND_FLAG = 'flag';
    public const KIND_QUOTA = 'quota';
    public const KIND_CUSTOM = 'custom';

    /** Reserved slug for the White Label add-on — its purchase/activation
     * flow lives entirely in InstituteWhiteLabelController, this catalog
     * row only supplies its pricing/listing. */
    public const SLUG_WHITE_LABEL = 'mobile_app_whitelabel';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'price',
        'billing_type',
        'kind',
        'quota_key',
        'quota_value',
        'features',
        'enabled',
    ];

    protected $casts = [
        'features' => 'array',
        'enabled' => 'boolean',
        'price' => 'float',
        'quota_value' => 'float',
    ];

    public function purchases()
    {
        return $this->hasMany(InstituteAddOnPurchase::class);
    }

    public static function whiteLabel(): ?self
    {
        return static::where('slug', self::SLUG_WHITE_LABEL)->first();
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price);
    }

    /**
     * The single canonical addon JSON shape — replaces four previously
     * independent array-builders across the API (InstituteWhiteLabelController,
     * Api/V1/PlanController, InstituteSubscriptionController) that had each
     * reimplemented this with slightly different fields.
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'billing_type' => $this->billing_type,
            'enabled' => $this->enabled,
            'features' => $this->features ?? [],
        ];
    }
}
