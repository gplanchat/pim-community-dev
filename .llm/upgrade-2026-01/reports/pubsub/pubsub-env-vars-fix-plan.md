# Plan to Work Around Missing PubSub Environment Variables

Date: 2026-01-14

## Problem Identified

The files `config/packages/test/messenger.yml` and `config/packages/behat/messenger.yml` use PubSub environment variables that are not defined:
- `PUBSUB_SUBSCRIPTION_BUSINESS_EVENT`
- `PUBSUB_TOPIC_BUSINESS_EVENT`
- `GOOGLE_CLOUD_PROJECT`
- `PUBSUB_AUTO_SETUP`
- And other PubSub variables...

## Analysis

1. **Current configuration**:
   - `config/packages/messenger.php` uses `TransportType::PUB_SUB` for `test` and `behat` environments
   - Static YAML files (`test/messenger.yml`, `behat/messenger.yml`) directly use PubSub with mandatory environment variables
   - `MessengerConfigBuilder` already uses `default::string:` for dynamically generated variables, but not for static YAML files

2. **Available options**:
   - Option A: Use default values in YAML files with Symfony syntax `%env(default::string:VAR_NAME:default_value)%`
   - Option B: Change transport to use `in-memory://` for tests (like `test_fake`)
   - Option C: Define environment variables in docker-compose.yml or .env
   - Option D: Use sync transport for tests

## Recommended Solution: Option A + Option B (hybrid)

**Strategy**: Use default values in YAML files AND allow switching to in-memory if necessary.

### Step 1: Add Default Values in YAML Files

Modify `config/packages/test/messenger.yml` and `config/packages/behat/messenger.yml` to use Symfony syntax with default values:

```yaml
framework:
    messenger:
        transports:
            business_event:
                dsn: 'gps:'
                options:
                    project_id: '%env(default::string:GOOGLE_CLOUD_PROJECT:test-project)%'
                    topic_name: '%env(default::string:PUBSUB_TOPIC_BUSINESS_EVENT:test-business-event-topic)%'
                    subscription_name: '%env(default::string:PUBSUB_SUBSCRIPTION_BUSINESS_EVENT:test-business-event-subscription)%'
                    auto_setup: '%env(default::bool:PUBSUB_AUTO_SETUP:false)%'
```

### Step 2: Alternative - Use in-memory for Tests

Modify `config/packages/messenger.php` to use `IN_MEMORY` for `test` instead of `PUB_SUB`:

```php
$transportType = match ($containerConfigurator->env()) {
    'behat' => TransportType::PUB_SUB,  // Keep PubSub for Behat if necessary
    'test', 'test_fake' => TransportType::IN_MEMORY,  // Use in-memory for tests
    default => TransportType::DOCTRINE,
};
```

**Advantage**: Tests no longer need PubSub configuration at all.

### Step 3: Define Variables in docker-compose.yml (temporary solution)

Add environment variables in `docker-compose.yml` for test containers:

```yaml
php:
    environment:
        # ... other variables ...
        PUBSUB_SUBSCRIPTION_BUSINESS_EVENT: 'test-business-event-subscription'
        PUBSUB_TOPIC_BUSINESS_EVENT: 'test-business-event-topic'
        GOOGLE_CLOUD_PROJECT: 'test-project'
        PUBSUB_AUTO_SETUP: 'false'
```

## Recommended Action Plan

### Phase 1: Quick Solution (Option C)
1. Add environment variables in `docker-compose.yml`
2. Test that tests work
3. Commit temporary solution

### Phase 2: Clean Solution (Option A)
1. Modify YAML files to use default values
2. Test that tests work without environment variables
3. Commit definitive solution

### Phase 3: Optimal Solution (Option B - optional)
1. Modify `config/packages/messenger.php` to use `IN_MEMORY` for `test`
2. Test that tests work with in-memory
3. Commit if this improves test performance

## Final Recommendation

**Start with Phase 1 (Option C)** to quickly unblock tests, then **implement Phase 2 (Option A)** for a cleaner and more maintainable solution.
