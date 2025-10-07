<?php

namespace ChurchPanel\Events\Models;

use ChurchPanel\AuditTrail\Traits\Auditable;
use ChurchPanel\CpCore\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceType extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'default_duration_minutes',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_duration_minutes' => 'integer',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($serviceType) {
            if (empty($serviceType->slug)) {
                $serviceType->slug = Str::slug($serviceType->name);
            }

            // Auto-increment display_order if not set
            if (!isset($serviceType->display_order)) {
                $maxOrder = static::where('branch_id', $serviceType->branch_id)
                    ->max('display_order');
                $serviceType->display_order = ($maxOrder ?? -1) + 1;
            }
        });

        static::updating(function ($serviceType) {
            if ($serviceType->isDirty('name') && empty($serviceType->slug)) {
                $serviceType->slug = Str::slug($serviceType->name);
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function church()
    {
        return $this->hasOneThrough(
            \ChurchPanel\CpCore\Models\Church::class,
            Branch::class,
            'id',
            'id',
            'branch_id',
            'church_id'
        );
    }

    // Placeholder for future events relationship
    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }

    /**
     * Scope to get only active service types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
