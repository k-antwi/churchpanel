<?php

namespace ChurchPanel\AuditTrail\Events;

use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class ModelUpdated extends Event
{
    #[StateId(AuditState::class)]
    public int|string|null $audit_id = null;

    public function __construct(
        public string $model_type,
        public int $model_id,
        public ?int $user_id,
        public array $old_values,
        public array $new_values,
        public string $ip_address,
        public ?string $user_agent = null,
    ) {
    }

    public function apply(AuditState $state): void
    {
        $state->event_type = 'updated';
        $state->model_type = $this->model_type;
        $state->model_id = $this->model_id;
        $state->user_id = $this->user_id;
        $state->old_values = $this->old_values;
        $state->new_values = $this->new_values;
        $state->ip_address = $this->ip_address;
        $state->user_agent = $this->user_agent;
        $state->created_at = now();
    }
}
