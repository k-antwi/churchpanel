<?php

namespace ChurchPanel\EvangelismCampaign\Models;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'visited_by',
        'visit_date',
        'purpose',
        'duration_minutes',
        'attendance_status',
        'notes',
        'prayer_requests',
        'needs_identified',
        'follow_up_required',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'follow_up_required' => 'boolean',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function visitor()
    {
        return $this->belongsTo(User::class, 'visited_by');
    }

    public function wasHome(): bool
    {
        return $this->attendance_status === 'home';
    }

    public function notHome(): bool
    {
        return $this->attendance_status === 'not_home';
    }

    public function hasMoved(): bool
    {
        return $this->attendance_status === 'moved';
    }
}
