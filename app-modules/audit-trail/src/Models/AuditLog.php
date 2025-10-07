<?php

namespace ChurchPanel\AuditTrail\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'verb_events';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'happened_at' => 'datetime',
    ];

    public function scopeAuditEvents($query)
    {
        return $query->whereIn('type', [
            'ChurchPanel\AuditTrail\Events\ModelCreated',
            'ChurchPanel\AuditTrail\Events\ModelUpdated',
            'ChurchPanel\AuditTrail\Events\ModelDeleted',
        ]);
    }

    public function getEventTypeAttribute()
    {
        return match(class_basename($this->type)) {
            'ModelCreated' => 'Created',
            'ModelUpdated' => 'Updated',
            'ModelDeleted' => 'Deleted',
            default => class_basename($this->type),
        };
    }

    public function getModelTypeAttribute()
    {
        return class_basename($this->data['model_type'] ?? '');
    }

    public function getModelIdAttribute()
    {
        return $this->data['model_id'] ?? null;
    }

    public function getUserAttribute()
    {
        $userId = $this->data['user_id'] ?? null;

        if (!$userId) {
            return 'System';
        }

        $user = \App\Models\User::find($userId);
        return $user ? $user->name : "User #{$userId}";
    }
}
