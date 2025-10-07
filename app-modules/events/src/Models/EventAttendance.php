<?php

namespace ChurchPanel\Events\Models;

use App\Models\User;
use ChurchPanel\AuditTrail\Traits\Auditable;
use ChurchPanel\People\Models\Contact;
use ChurchPanel\People\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EventAttendance extends Model
{
    use HasFactory;

    protected $table = 'event_attendance';

    protected $fillable = [
        'event_id',
        'attendanceable_id',
        'attendanceable_type',
        'attendance_status',
        'check_in_time',
        'check_out_time',
        'checked_in_by',
        'check_in_method',
        'notes',
        'first_time_visitor',
        'brought_by',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'first_time_visitor' => 'boolean',
    ];

    /**
     * Get the event that owns the attendance record
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the attendee (polymorphic - can be Person or Contact)
     */
    public function attendanceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who checked in this attendee
     */
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Get the person who brought this attendee (for first-time visitors)
     */
    public function broughtBy(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'brought_by');
    }

    /**
     * Scope to get only present attendees
     */
    public function scopePresent($query)
    {
        return $query->where('attendance_status', 'present');
    }

    /**
     * Scope to get only absent attendees
     */
    public function scopeAbsent($query)
    {
        return $query->where('attendance_status', 'absent');
    }

    /**
     * Scope to get only late attendees
     */
    public function scopeLate($query)
    {
        return $query->where('attendance_status', 'late');
    }

    /**
     * Scope to get only excused attendees
     */
    public function scopeExcused($query)
    {
        return $query->where('attendance_status', 'excused');
    }

    /**
     * Scope to get first-time visitors
     */
    public function scopeFirstTimeVisitors($query)
    {
        return $query->where('first_time_visitor', true);
    }

    /**
     * Scope to get attendees checked in by a specific method
     */
    public function scopeByCheckInMethod($query, string $method)
    {
        return $query->where('check_in_method', $method);
    }

    /**
     * Check if attendee is present
     */
    public function isPresent(): bool
    {
        return $this->attendance_status === 'present';
    }

    /**
     * Check if attendee is a first-time visitor
     */
    public function isFirstTimeVisitor(): bool
    {
        return $this->first_time_visitor === true;
    }

    /**
     * Get duration of attendance in minutes
     */
    public function getDurationInMinutes(): ?int
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInMinutes($this->check_out_time);
        }

        return null;
    }

    /**
     * Get attendee name (works for both Person and Contact)
     */
    public function getAttendeeName(): ?string
    {
        if ($this->attendanceable instanceof Person) {
            return $this->attendanceable->first_name . ' ' . $this->attendanceable->last_name;
        }

        if ($this->attendanceable instanceof Contact) {
            return $this->attendanceable->name;
        }

        return null;
    }
}
