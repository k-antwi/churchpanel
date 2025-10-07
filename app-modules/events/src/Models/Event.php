<?php

namespace ChurchPanel\Events\Models;

use App\Models\User;
use ChurchPanel\AuditTrail\Traits\Auditable;
use ChurchPanel\CpCore\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'branch_id',
        'service_type_id',
        'title',
        'description',
        'location',
        'start_datetime',
        'end_datetime',
        'recurrence_pattern',
        'recurrence_ends_at',
        'expected_attendees',
        'coordinator_id',
        'status',
        'notes',
        'settings',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'recurrence_ends_at' => 'date',
        'recurrence_pattern' => 'array',
        'settings' => 'array',
        'expected_attendees' => 'integer',
    ];

    /**
     * Get the branch that owns the event
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the church through the branch
     */
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

    /**
     * Get the service type that owns the event
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the coordinator (user) for the event
     */
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    /**
     * Get the attendance records for this event
     */
    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    /**
     * Scope to get only scheduled events
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope to get only completed events
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get only cancelled events
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope to get upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('start_datetime');
    }

    /**
     * Scope to get past events
     */
    public function scopePast($query)
    {
        return $query->where('end_datetime', '<', now())
            ->orderBy('start_datetime', 'desc');
    }

    /**
     * Scope to get recurring events
     */
    public function scopeRecurring($query)
    {
        return $query->whereNotNull('recurrence_pattern');
    }

    /**
     * Check if event is recurring
     */
    public function isRecurring(): bool
    {
        return !is_null($this->recurrence_pattern);
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->start_datetime > now() && $this->status === 'scheduled';
    }

    /**
     * Get duration in minutes
     */
    public function getDurationInMinutes(): int
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime);
    }
}
