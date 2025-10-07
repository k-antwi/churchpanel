<?php

namespace ChurchPanel\EvangelismCampaign\Models;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscipleshipJourney extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'stage',
        'started_at',
        'completed_at',
        'mentor_id',
        'notes',
        'materials_given',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'materials_given' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($journey) {
            if (!$journey->started_at) {
                $journey->started_at = now();
            }
        });
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function markAsCompleted()
    {
        $this->update(['completed_at' => now()]);
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    public function getStageLabel(): string
    {
        return match($this->stage) {
            'new_convert' => 'New Convert',
            'baptism_prep' => 'Baptism Preparation',
            'baptized' => 'Baptized',
            'foundation_class' => 'Foundation Class',
            'membership_class' => 'Membership Class',
            'maturity_class' => 'Maturity Class',
            'leadership_training' => 'Leadership Training',
            'serving' => 'Serving',
            default => $this->stage,
        };
    }
}
