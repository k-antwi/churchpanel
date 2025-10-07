<?php

namespace ChurchPanel\EvangelismCampaign\Models;

use ChurchPanel\CpCore\Models\Branch;
use ChurchPanel\CpCore\Models\Church;
use ChurchPanel\People\Models\Contact;
use App\Models\FollowUp;
use App\Models\User;
use App\Models\Visitation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvangelismCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'branch_id',
        'title',
        'description',
        'type',
        'location',
        'start_date',
        'end_date',
        'target_souls',
        'coordinator_id',
        'budget',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'evangelism_campaign_team')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'evangelism_campaign_contact')
            ->withTimestamps();
    }

    public function capturedContacts()
    {
        return $this->hasMany(Contact::class, 'evangelism_campaign_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function visitations()
    {
        return $this->hasManyThrough(
            Visitation::class,
            Contact::class,
            'id',
            'contact_id',
            'id',
            'id'
        )->whereIn('contact_id', function ($query) {
            $query->select('contact_id')
                ->from('evangelism_campaign_contact')
                ->where('evangelism_campaign_id', $this->id);
        });
    }

    public function getReachedSoulsAttribute()
    {
        return $this->contacts()->count();
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->target_souls || $this->target_souls == 0) {
            return 0;
        }
        return min(100, round(($this->reached_souls / $this->target_souls) * 100, 2));
    }
}
