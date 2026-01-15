# Solutions Applied to Fix Test Configuration Issues

Date: 2026-01-14

## Modifications Applied

### 1. PHPUnit - APP_ENV Configuration

**File modified**: `phpunit.xml`

**Change**:
```xml
<php>
    <!-- ... other configurations ... -->
    <env name="APP_ENV" value="test" force="true" />
</php>
```

**Reason**: Forces "test" environment for PHPUnit, ensuring `config/packages/test/framework.yml` is loaded with `framework.test: true`.

### 2. PHPUnit - Force Environment in TestCase

**File modified**: `tests/back/Integration/TestCase.php`

**Change**:
```php
protected function setUp(): void
{
    // Ensure APP_ENV is set to 'test' for PHPUnit
    $_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'test';
    
    $this->testKernel = static::bootKernel([
        'environment' => 'test',
        'debug' => (bool)($_SERVER['APP_DEBUG'] ?? false)
    ]);
    // ...
}
```

**Reason**: Explicitly forces "test" environment when booting Kernel, ensuring `test.service_container` is available.

### 3. Behat - Composer Autoload

**Action**: Ran `composer dump-autoload`

**Reason**: Regenerates autoloader to ensure all classes are properly loaded, especially `Context\FeatureContext`.

## Test Results

### PHPUnit
- ✅ **RESOLVED**: Tests now start without `test.service_container` error
- ✅ "test" environment is properly loaded
- ⚠️ PHP 8.4 deprecation warnings appear (normal, related to Symfony 5.4)
- ⚠️ New error detected: Missing environment variable `PUBSUB_SUBSCRIPTION_BUSINESS_EVENT` (application configuration issue, not related to PHP 8.4)

### Behat
- ⚠️ Behat suites require `legacy:critical` format (not just `critical` or `legacy`)
- ⏳ To be tested with correct suite format

## Next Steps

1. [ ] Test PHPUnit on a sample of tests to confirm `test.service_container` works
2. [ ] Test Behat with `--suite=legacy` to verify FeatureContext is found
3. [ ] Document results in tracking files
4. [ ] Commit modifications

## Notes

- PHP 8.4 deprecation warnings are normal and will be resolved during Symfony 8.0 migration
- Modifications are minimal and targeted to resolve specific issues
- These fixes are not related to PHP 8.4 but to Symfony/Behat configuration
