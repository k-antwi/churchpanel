<?php

namespace ChurchPanel\People\Models;

use App\Models\User;
use ChurchPanel\AuditTrail\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellbeingRecord extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'recorded_by',
        'recordable_id',
        'recordable_type',
        'record_date',
        'type',
        'status',
        'prayer_requests',
        'needs',
        'assistance_provided',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (!$record->recorded_by) {
                $record->recorded_by = auth()->id();
            }
            if (!$record->record_date) {
                $record->record_date = now()->toDateString();
            }
        });
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function recordable()
    {
        return $this->morphTo();
    }
}
