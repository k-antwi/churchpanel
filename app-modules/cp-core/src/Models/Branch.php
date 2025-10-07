<?php

namespace ChurchPanel\CpCore\Models;

use App\Models\User;
use ChurchPanel\People\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'name',
        'slug',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'pastor_id',
        'settings',
        'is_main',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_main' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($branch) {
            if (empty($branch->slug)) {
                $slug = Str::slug($branch->name);
                $count = 1;
                while (self::where('slug', $slug)->exists()) {
                    $slug = Str::slug($branch->name) . '-' . $count;
                    $count++;
                }
                $branch->slug = $slug;
            }
        });
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function pastor()
    {
        return $this->belongsTo(User::class, 'pastor_id');
    }

    public function people()
    {
        return $this->belongsToMany(Person::class, 'branch_contacts')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'branch_user')
            ->withTimestamps();
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function evangelismCampaigns()
    {
        return $this->hasMany(\ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign::class);
    }
}
