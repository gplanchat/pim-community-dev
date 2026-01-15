# Solutions Applied to Work Around Missing PubSub Environment Variables

Date: 2026-01-14

## Problem Resolved

PHPUnit and Behat tests were failing with error:
```
Environment variable not found: "PUBSUB_SUBSCRIPTION_BUSINESS_EVENT"
```

## Solutions Applied

### Phase 1: Added Variables in docker-compose.yml ✅

**File modified**: `docker-compose.yml`

**Changes**:
- Added all necessary PubSub variables in `php` and `httpd` containers
- Default values defined for test environment
- Variables use `${VAR:-default}` syntax to allow override

**Variables added**:
- `GOOGLE_CLOUD_PROJECT` (default: `test-project`)
- `PUBSUB_AUTO_SETUP` (default: `false`)
- `PUBSUB_TOPIC_BUSINESS_EVENT` (default: `test-business-event-topic`)
- `PUBSUB_SUBSCRIPTION_BUSINESS_EVENT` (default: `test-business-event-subscription`)
- `PUBSUB_SUBSCRIPTION_WEBHOOK` (default: `test-webhook-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_UI` (default: `test-ui-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_UI` (default: `test-ui-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_IMPORT_EXPORT` (default: `test-import-export-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_IMPORT_EXPORT` (default: `test-import-export-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_DATA_MAINTENANCE` (default: `test-data-maintenance-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_DATA_MAINTENANCE` (default: `test-data-maintenance-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_SCHEDULED_JOB` (default: `test-scheduled-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_SCHEDULED_JOB` (default: `test-scheduled-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_PAUSED_JOB` (default: `test-paused-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_PAUSED_JOB` (default: `test-paused-job-subscription`)

### Phase 2: Added Default Values in YAML Files ✅

**Files modified**:
- `config/packages/test/messenger.yml`
- `config/packages/behat/messenger.yml`

**Changes**:
- Replaced `%env(VAR_NAME)%` with `%env(default::string:VAR_NAME:default_value)%`
- Replaced `%env(bool:VAR_NAME)%` with `%env(default::bool:VAR_NAME:false)%`
- Allows tests to work even if variables are not defined in environment

**Example change**:
```yaml
# Before
project_id: '%env(GOOGLE_CLOUD_PROJECT)%'

# After
project_id: '%env(default::string:GOOGLE_CLOUD_PROJECT:test-project)%'
```

## Results

### PHPUnit
- ✅ **RESOLVED**: No more "Environment variable not found" error
- ✅ Tests start correctly
- ⚠️ Test errors remain (not related to PubSub configuration)

### Behat
- ✅ **RESOLVED**: No more "Environment variable not found" error
- ✅ FeatureContext is found and works
- ✅ Features and scenarios are detected correctly

## Solution Advantages

1. **Double protection**: Variables are defined in docker-compose.yml AND have default values in YAML files
2. **Flexibility**: Values can be overridden via environment variables
3. **Maintainability**: Default values are documented in configuration files
4. **Compatibility**: Works with or without PubSub emulator

## Notes

- These modifications are not related to PHP 8.4 but to Symfony Messenger/PubSub configuration
- Default values are specific to tests and should not be used in production
- PubSub emulator (`pubsub-emulator`) is available in docker-compose.yml for local tests
