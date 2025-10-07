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
        'evangelism_campaign_id',
        'person_id',
        'first_name',
        'last_name',
        'email',
        'address',
        'mobile',
        'social_handle',
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

    public function capturedInCampaign()
    {
        return $this->belongsTo(\ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign::class, 'evangelism_campaign_id');
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
        return $this->hasMany(\ChurchPanel\EvangelismCampaign\Models\DiscipleshipJourney::class);
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

    /**
     * Graduate this contact to the people table
     */
    public function graduate(): \ChurchPanel\People\Models\Person
    {
        // If already linked to a person, update that person's data
        if ($this->person_id) {
            $person = $this->person;
            $person->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email ?? $person->email,
                'address_line' => $this->address ?? $person->address_line,
                'mobile_phone' => $this->mobile ?? $person->mobile_phone,
            ]);
        } else {
            // Create a new person record
            $person = \ChurchPanel\People\Models\Person::create([
                'church_id' => $this->church_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'address_line' => $this->address,
                'mobile_phone' => $this->mobile,
                'type' => 'member',
            ]);

            // Link the contact to the person
            $this->person_id = $person->id;
        }

        // Mark contact as graduated
        $this->stage = 'graduated';
        $this->save();

        return $person;
    }

    /**
     * Check if contact is graduated
     */
    public function isGraduated(): bool
    {
        return $this->stage === 'graduated';
    }
}
