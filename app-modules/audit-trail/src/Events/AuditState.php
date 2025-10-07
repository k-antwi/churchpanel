<?php

namespace ChurchPanel\AuditTrail\Events;

use Thunk\Verbs\State;

class AuditState extends State
{
    public string $event_type;
    public string $model_type;
    public int $model_id;
    public ?int $user_id = null;
    public array $old_values = [];
    public array $new_values = [];
    public string $ip_address;
    public ?string $user_agent = null;
    public \DateTimeInterface $created_at;
}
