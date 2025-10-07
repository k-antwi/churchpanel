# Audit Trail Module

This module provides comprehensive audit logging functionality for ChurchPanel using the **hirethunk/verbs** event sourcing package.

## Features

- Automatic tracking of model creation, updates, and deletions
- Event sourcing architecture using Thunk Verbs
- User attribution (who made the change)
- IP address and user agent tracking
- Filament admin interface for viewing audit logs
- Easy integration with any Eloquent model

## Installation

The module is already installed. The audit trail tables are created automatically during migration.

## Usage

### Adding Audit Trail to Models

To enable audit logging for a model, simply add the `Auditable` trait:

```php
use ChurchPanel\AuditTrail\Traits\Auditable;

class YourModel extends Model
{
    use Auditable;
}
```

### Customizing Auditable Columns

By default, all fillable columns are audited. You can customize this:

```php
class YourModel extends Model
{
    use Auditable;

    // Only audit these columns
    protected array $auditableColumns = ['name', 'email', 'status'];

    // Exclude these columns from audit
    protected array $auditExclude = ['temporary_field', 'cache_data'];

    // Only audit these events (created, updated, deleted)
    protected static array $auditEvents = ['created', 'updated'];
}
```

### Viewing Audit Logs

Audit logs are available in the Filament admin panel under:
**System → Audit Logs**

The audit log view shows:
- Event type (Created, Updated, Deleted)
- Model type and ID
- User who made the change
- IP address
- Timestamp

## Architecture

The audit trail uses event sourcing with the following components:

### Events
- `ModelCreated` - Fired when a model is created
- `ModelUpdated` - Fired when a model is updated
- `ModelDeleted` - Fired when a model is deleted

### State
- `AuditState` - Stores the audit information

### Trait
- `Auditable` - Add this trait to any model to enable audit logging

## Currently Audited Models

- Contact

## Adding More Models

To add audit trail to additional models, simply add the `Auditable` trait to the model class.

Example:
```php
use ChurchPanel\AuditTrail\Traits\Auditable;

class Branch extends Model
{
    use Auditable;
}
```

## Technical Details

- Uses **hirethunk/verbs** for event sourcing
- Events are stored in the `verb_events` table
- State snapshots are stored in the `verb_snapshots` table
- Automatically captures user context, IP address, and user agent
