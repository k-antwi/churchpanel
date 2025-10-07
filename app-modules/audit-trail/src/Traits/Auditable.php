<?php

namespace ChurchPanel\AuditTrail\Traits;

use ChurchPanel\AuditTrail\Events\ModelCreated;
use ChurchPanel\AuditTrail\Events\ModelDeleted;
use ChurchPanel\AuditTrail\Events\ModelUpdated;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            if (static::shouldAudit('created')) {
                ModelCreated::fire(
                    model_type: get_class($model),
                    model_id: $model->id,
                    user_id: auth()->id(),
                    new_values: $model->getAuditableAttributes(),
                    ip_address: request()->ip() ?? '127.0.0.1',
                    user_agent: request()->userAgent(),
                );
            }
        });

        static::updated(function ($model) {
            if (static::shouldAudit('updated') && $model->wasChanged($model->getAuditableColumns())) {
                ModelUpdated::fire(
                    model_type: get_class($model),
                    model_id: $model->id,
                    user_id: auth()->id(),
                    old_values: $model->getOriginal(),
                    new_values: $model->getChanges(),
                    ip_address: request()->ip() ?? '127.0.0.1',
                    user_agent: request()->userAgent(),
                );
            }
        });

        static::deleted(function ($model) {
            if (static::shouldAudit('deleted')) {
                ModelDeleted::fire(
                    model_type: get_class($model),
                    model_id: $model->id,
                    user_id: auth()->id(),
                    old_values: $model->getAuditableAttributes(),
                    ip_address: request()->ip() ?? '127.0.0.1',
                    user_agent: request()->userAgent(),
                );
            }
        });
    }

    protected function getAuditableAttributes(): array
    {
        $attributes = $this->getAttributes();

        // Remove excluded attributes
        $excluded = array_merge(
            $this->getAuditExclude(),
            ['password', 'remember_token']
        );

        return array_diff_key($attributes, array_flip($excluded));
    }

    protected function getAuditableColumns(): array
    {
        if (property_exists($this, 'auditableColumns')) {
            return $this->auditableColumns;
        }

        // Get all fillable columns
        return $this->getFillable();
    }

    protected function getAuditExclude(): array
    {
        return property_exists($this, 'auditExclude') ? $this->auditExclude : [];
    }

    protected static function shouldAudit(string $event): bool
    {
        if (property_exists(static::class, 'auditEvents')) {
            return in_array($event, static::$auditEvents);
        }

        // By default, audit all events
        return true;
    }
}
