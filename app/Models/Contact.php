<?php

namespace App\Models;

use ChurchPanel\People\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'branch_id',
        'person_id',
        'age_group',
        'marital_status',
        'occupation',
        'contact_source',
        'notes',
        'captured_by',
        'captured_at',
        'stage',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contact) {
            if (!$contact->captured_by) {
                $contact->captured_by = auth()->id();
            }
            if (!$contact->captured_at) {
                $contact->captured_at = now();
            }
        });
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function capturedBy()
    {
        return $this->belongsTo(User::class, 'captured_by');
    }

    public function followUps()
    {
        return $this->hasMany(\ChurchPanel\EvangelismCampaign\Models\FollowUp::class);
    }

    public function visitations()
    {
        return $this->hasMany(Visitation::class);
    }

    public function discipleshipJourneys()
    {
        return $this->hasMany(DiscipleshipJourney::class);
    }

    public function wellbeingRecords()
    {
        return $this->hasMany(WellbeingRecord::class);
    }

    public function evangelismCampaigns()
    {
        return $this->belongsToMany(\ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign::class, 'evangelism_campaign_contact')
            ->withTimestamps();
    }
}
