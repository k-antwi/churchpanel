<?php

namespace ChurchPanel\CpCore\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Church extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'logo',
        'cover_image',
        'pastor_id',
        'is_active',
        'social_media',
        'service_times',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'social_media' => 'array',
        'service_times' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($church) {
            if (empty($church->slug)) {
                $church->slug = Str::slug($church->name);
            }
        });

        static::updating(function ($church) {
            if ($church->isDirty('name') && empty($church->slug)) {
                $church->slug = Str::slug($church->name);
            }
        });
    }

    public function pastor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pastor_id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function evangelismCampaigns()
    {
        return $this->hasMany(\ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign::class);
    }

    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }
}
