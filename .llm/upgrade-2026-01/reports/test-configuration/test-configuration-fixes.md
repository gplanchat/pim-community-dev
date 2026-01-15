# Solutions to Fix Test Configuration Issues

Date: 2026-01-14
Issues identified: PHPUnit test.service_container and Behat FeatureContext

## Issue 1: PHPUnit - Missing test.service_container

### Symptoms
```
LogicException: Could not find service "test.service_container". Try updating the "framework.test" config to "true".
```

### Analysis
- The file `config/packages/test/framework.yml` exists and contains `framework.test: true`
- The problem seems to be that PHPUnit is not using the "test" environment correctly
- The `TestCase::get()` method uses `static::getContainer()->get($service)` which should work with Symfony 5.4

### Proposed Solutions

#### Solution 1: Verify PHPUnit Environment
The `phpunit.xml` file must define the Symfony environment correctly.

**Action**: Verify that `phpunit.xml` properly loads the "test" environment via `KERNEL_CLASS`.

**File**: `phpunit.xml`
```xml
<php>
    <server name="KERNEL_CLASS" value="Kernel" force="true" />
    <env name="APP_ENV" value="test" force="true" />
</php>
```

#### Solution 2: Verify PHPUnit Bootstrap
The `config/bootstrap.php` file must initialize the environment correctly.

**Action**: Verify that `config/bootstrap.php` defines `$_SERVER['APP_ENV'] = 'test'` if not defined.

#### Solution 3: Use KernelTestCase::getContainer() Directly
Instead of using `test.service_container`, use `getContainer()` from KernelTestCase directly.

**File**: `tests/back/Integration/TestCase.php`
```php
protected function get(string $service)
{
    // Use getContainer() directly instead of test.service_container
    return static::getContainer()->get($service);
}
```

**Note**: This method should already work with Symfony 5.4. The problem might be that `getContainer()` is not initialized correctly.

#### Solution 4: Verify Symfony FrameworkBundle Configuration
Ensure FrameworkBundle is properly configured for test environment.

**File**: `config/packages/test/framework.yml` (already exists)
```yaml
framework:
    test: true
    session:
        storage_id: session.storage.mock_file
```

**Verification**: File already exists with correct configuration.

#### Solution 5: Verify Kernel Initialization in Tests
The Kernel must be booted with "test" environment.

**File**: `tests/back/Integration/TestCase.php`
```php
protected function setUp(): void
{
    // Ensure APP_ENV is set to 'test'
    $_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'test';
    
    $this->testKernel = static::bootKernel([
        'environment' => 'test',  // Force environment
        'debug' => (bool)($_SERVER['APP_DEBUG'] ?? false)
    ]);
    // ...
}
```

### Recommended Solution
**Combination of Solution 1 + Solution 5**: 
1. Add `APP_ENV=test` in `phpunit.xml`
2. Force environment in `TestCase::setUp()`

---

## Issue 2: Behat - FeatureContext Not Found

### Symptoms
```
`FeatureContext` context class not found and can not be used.
```

### Analysis
- The `Context\FeatureContext` class exists in `tests/legacy/features/Context/FeatureContext.php`
- Autoloading works (tested with `class_exists()`)
- The problem is that Behat cannot find the class during execution

### Proposed Solutions

#### Solution 1: Verify Composer Autoloading
Ensure the `Context` namespace is properly autoloaded.

**File**: `composer.json`
```json
{
    "autoload": {
        "psr-4": {
            "Context\\": "tests/legacy/features/Context/"
        }
    }
}
```

**Action**: Verify if `Context` namespace is in autoload. If not, add it and run `composer dump-autoload`.

#### Solution 2: Verify Behat Bootstrap
The `config/bootstrap.php` file must be loaded correctly.

**File**: `behat.yml`
```yaml
extensions:
    FriendsOfBehat\SymfonyExtension:
        bootstrap: config/bootstrap.php
        kernel:
            path: src/Kernel.php
            class: Kernel
            environment: behat
            debug: false
```

**Verification**: Configuration seems correct.

#### Solution 3: Verify Behat Environment
Behat uses "behat" environment, not "test". Verify that Context classes are available in this environment.

**Action**: Verify that `config/bootstrap.php` properly loads Composer autoloader.

#### Solution 4: Add Context Namespace to Autoload
If namespace is not in composer.json, add it explicitly.

**File**: `composer.json`
```json
{
    "autoload": {
        "psr-4": {
            "Context\\": "tests/legacy/features/Context/"
        }
    }
}
```

**Action**: Run `composer dump-autoload` after modification.

#### Solution 5: Verify FeatureContext Path in behat.yml
The context is referenced as `Context\FeatureContext` in behat.yml, which is correct.

**File**: `behat.yml`
```yaml
contexts: &context
    -   Context\FeatureContext
```

**Verification**: Configuration seems correct.

### Recommended Solution
**Solution 1 + Solution 4**: 
1. Verify/add `Context` namespace in `composer.json` autoload
2. Run `composer dump-autoload`
3. Verify that `config/bootstrap.php` properly loads autoloader

---

## Action Plan

### Step 1: Fix PHPUnit
1. [ ] Verify `phpunit.xml` - add `APP_ENV=test` if necessary
2. [ ] Modify `tests/back/Integration/TestCase.php` to force "test" environment
3. [ ] Run PHPUnit to verify: `docker compose run --rm php vendor/bin/phpunit --testdox | head -50`

### Step 2: Fix Behat
1. [ ] Verify `composer.json` - add `Context` namespace if necessary
2. [ ] Run `composer dump-autoload`
3. [ ] Verify `config/bootstrap.php` loads autoloader
4. [ ] Run Behat to verify: `docker compose run --rm php vendor/bin/behat --dry-run`

### Step 3: Validation Tests
1. [ ] Run PHPUnit on a few tests: `docker compose run --rm php vendor/bin/phpunit tests/back/Integration/TestCase.php`
2. [ ] Run Behat with a simple suite: `docker compose run --rm php vendor/bin/behat --suite=critical --dry-run`

---

## Important Notes

- These issues are **NOT related to PHP 8.4** - they are Symfony/Behat configuration issues
- Solutions must be tested one by one to identify the exact cause
- Once fixed, these issues will allow all tests to run correctly
