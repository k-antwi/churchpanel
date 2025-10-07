<?php

namespace ChurchPanel\EvangelismCampaign\Models;

use ChurchPanel\People\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'evangelism_campaign_id',
        'assigned_to',
        'type',
        'scheduled_date',
        'completed_at',
        'status',
        'notes',
        'outcome',
        'next_action',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function evangelismCampaign()
    {
        return $this->belongsTo(EvangelismCampaign::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function markAsCancelled()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }
}
