<?php

namespace ChurchPanel\People\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'email',
        'church_id',
        'type',
        'empty',
        'last_ip',
        'date_of_birth',
        'address_line',
        'town',
        'city',
        'country',
        'county',
        'postcode',
        'map_url',
        'mobile_phone',
        'phone',
        'bio',
        'site',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function church()
    {
        return $this->belongsTo(\App\Models\Church::class);
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }

    public function branches()
    {
        return $this->belongsToMany(\App\Models\Branch::class, 'branch_contacts')
            ->withTimestamps();
    }

    public function contacts()
    {
        return $this->hasMany(\App\Models\Contact::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->title} {$this->first_name} {$this->last_name}");
    }
}
