# Dependencies Phase Mapping Analysis

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Overview

This document analyzes all project dependencies and maps them to appropriate migration phases based on their PHP/Symfony version requirements.

## Analysis Methodology

1. **Read `composer.json`** - Extract all dependencies
2. **Identify version constraints** - Check current version constraints
3. **Check compatibility** - Determine PHP/Symfony version requirements
4. **Map to phases** - Assign dependencies to appropriate migration phases
5. **Group by phase** - Organize dependencies by when they should be updated

## Dependency Categories

### Category A: Core Symfony Components
- Must be updated during Symfony migration (Phase 5)
- Direct dependencies on Symfony version

### Category B: Symfony Bundles
- Must be updated during Symfony migration (Phase 5)
- Require specific Symfony versions

### Category C: PHP Version Dependent
- Must be updated during PHP migration (Phase 2 or Phase 6)
- Require specific PHP versions

### Category D: Independent
- Can be updated at any time
- No specific PHP/Symfony requirements

### Category E: Development Tools
- Updated in Phase 7 (Development Tools)
- Testing, linting, code quality tools

## Current Dependency State Analysis

**Note**: The `composer.json` shows `"php": "^8.4"` which suggests partial migration may have started. Verify current state before proceeding.

### Key Findings from `composer outdated`:

1. **Doctrine packages** have major updates available:
   - `doctrine/orm`: 2.13.3 → 3.6.1 (major version jump)
   - `doctrine/dbal`: 2.13.9 → 4.4.1 (major version jump)
   - `doctrine/doctrine-bundle`: 2.7.1 → 3.2.2 (major version jump)

2. **Abandoned packages** (should be replaced):
   - `doctrine/annotations`: Abandoned (project uses YAML mappings, can be removed)
   - `doctrine/cache`: Abandoned (ORM 3 uses PSR-6, can be removed in Phase 9)

3. **Symfony components**: All at 5.4.0, need update to 8.0

4. **Monolog**: 2.8.0 → 3.10.0 (major version jump, may require Symfony 8.0)

## Phase Mapping

### Phase 0: Pre-Migration Validation
**No dependency updates** - Only validation and error tracking

### Phase 2: PHP 8.1 → 8.4 Migration

#### Dependencies Requiring PHP 8.2+
- [ ] **Check each dependency** for PHP 8.2+ requirements
- [ ] **Update if incompatible** with PHP 8.2

#### Dependencies Requiring PHP 8.3+
- [ ] **Check each dependency** for PHP 8.3+ requirements
- [ ] **Update if incompatible** with PHP 8.3

#### Dependencies Requiring PHP 8.4+
- [ ] **Check each dependency** for PHP 8.4+ requirements
- [ ] **Update if incompatible** with PHP 8.4

**Note**: Most dependencies compatible with PHP 8.1 should work with PHP 8.4. Only update if specific incompatibilities are found.

#### Dependencies That May Need Updates (Check Compatibility)
- [ ] `monolog/monolog`: `^2.8.0` → Check PHP 8.4 compatibility (may need 3.x for Symfony 8.0)
- [ ] `elasticsearch/elasticsearch`: `7.11.0` → Check PHP 8.4 compatibility
- [ ] `guzzlehttp/guzzle`: `^7.5.0` → Check PHP 8.4 compatibility (likely OK)
- [ ] `league/flysystem`: `^3.11.0` → Check PHP 8.4 compatibility (likely OK)
- [ ] All other dependencies: Check individually if issues arise

### Phase 3: TypeScript 4.0 → 5.6 Migration

#### JavaScript/TypeScript Dependencies
- [ ] **TypeScript**: `^4.0.3` → `^5.6.0`
- [ ] **@types/react**: `^17.0.2` → `^19.0.0` (after React migration)
- [ ] **@types/react-dom**: `^17.0.2` → `^19.0.0` (after React migration)
- [ ] **All TypeScript-dependent packages** - Check compatibility

**Can be done in parallel** with PHP/Symfony migrations

### Phase 4: React 17 → 19 Migration

#### React Dependencies
- [ ] **react**: `^17.0.2` → `^19.0.0`
- [ ] **react-dom**: `^17.0.2` → `^19.0.0`
- [ ] **styled-components**: `^5.1.1` → Check React 19 compatibility
- [ ] **All React-dependent packages** - Check compatibility

**Can be done in parallel** with PHP/Symfony migrations

### Phase 5: Symfony 5.4 → 8.0 Migration

#### Core Symfony Components (MUST UPDATE)
All `symfony/*` packages must be updated together:

- [ ] `symfony/asset`: `^5.4.0` → `^8.0`
- [ ] `symfony/cache`: `^5.4` → `^8.0`
- [ ] `symfony/config`: `^5.4.0` → `^8.0`
- [ ] `symfony/dependency-injection`: `^5.4.0` → `^8.0`
- [ ] `symfony/doctrine-messenger`: `^5.4.0` → `^8.0`
- [ ] `symfony/dotenv`: `^5.4.0` → `^8.0`
- [ ] `symfony/event-dispatcher`: `^5.4.0` → `^8.0`
- [ ] `symfony/filesystem`: `^5.4.0` → `^8.0`
- [ ] `symfony/finder`: `^5.4.0` → `^8.0`
- [ ] `symfony/flex`: `^1.16.1` → Check Symfony 8.0 compatibility
- [ ] `symfony/form`: `^5.4.0` → `^8.0`
- [ ] `symfony/http-foundation`: `^5.4.0` → `^8.0`
- [ ] `symfony/http-kernel`: `^5.4.0` → `^8.0`
- [ ] `symfony/intl`: `^5.4.0` → `^8.0`
- [ ] `symfony/lock`: `^5.4.0` → `^8.0`
- [ ] `symfony/mailer`: `^5.4.0` → `^8.0`
- [ ] `symfony/messenger`: `^5.4.0` → `^8.0`
- [ ] `symfony/monolog-bundle`: `^3.8.0` → Check Symfony 8.0 compatibility
- [ ] `symfony/options-resolver`: `^5.4.0` → `^8.0`
- [ ] `symfony/polyfill-apcu`: `^1.23.0` → Check if still needed
- [ ] `symfony/process`: `^5.4.0` → `^8.0`
- [ ] `symfony/property-access`: `^5.4.0` → `^8.0`
- [ ] `symfony/proxy-manager-bridge`: `^5.4.0` → `^8.0`
- [ ] `symfony/requirements-checker`: `^2.0.0` → Check Symfony 8.0 compatibility
- [ ] `symfony/routing`: `^5.4.0` → `^8.0`
- [ ] `symfony/security-acl`: `^3.1.1` → Check Symfony 8.0 compatibility (may be deprecated)
- [ ] `symfony/security-bundle`: `^5.4.0` → `^8.0`
- [ ] `symfony/security-core`: `^5.4.0` → `^8.0`
- [ ] `symfony/security-csrf`: `^5.4.0` → `^8.0`
- [ ] `symfony/security-http`: `^5.4.0` → `^8.0`
- [ ] `symfony/templating`: `^5.4.0` → `^8.0`
- [ ] `symfony/translation`: `^5.4.0` → `^8.0`
- [ ] `symfony/twig-bundle`: `^5.4.0` → `^8.0`
- [ ] `symfony/validator`: `^5.4.0` → `^8.0`
- [ ] `symfony/yaml`: `^5.4.0` → `^8.0`

#### Symfony Bundles (MUST UPDATE)
- [ ] `symfony/debug-bundle`: `^5.4.0` → `^8.0` (dev dependency)
- [ ] `symfony/web-profiler-bundle`: `^5.4.0` → `^8.0` (dev dependency)

#### Third-Party Symfony Bundles (CHECK COMPATIBILITY)
- [ ] `doctrine/doctrine-bundle`: `^2.5.5` → Check Symfony 8.0 compatibility (keep ORM 2.x, see `00-doctrine-orm-3-analysis.md`)
- [ ] `doctrine/doctrine-migrations-bundle`: `^3.2.0` → Check Symfony 8.0 compatibility
- [ ] `doctrine/doctrine-fixtures-bundle`: `^3.4.1` → Check Symfony 8.0 compatibility
- [ ] `friends-of-behat/symfony-extension`: `^2.4.0` → Check Symfony 8.0 compatibility
- [ ] `friendsofsymfony/jsrouting-bundle`: Check if present, update for Symfony 8.0
- [ ] `friendsofsymfony/rest-bundle`: Check if present, update for Symfony 8.0
- [ ] `liip/imagine-bundle`: Check if present, update for Symfony 8.0

#### Twig (Symfony Dependent)
- [ ] `twig/twig`: `^3.3.3` → Check Symfony 8.0 compatibility (likely `^3.8` or `^4.0`)
- [ ] **Note**: Symfony 8.0 may require Twig 4.0, check compatibility

#### Monolog (May Require Update)
- [ ] `monolog/monolog`: `^2.8.0` → Check Symfony 8.0 compatibility
- [ ] **Likely**: Need `^3.0` for Symfony 8.0 (Monolog 3.x required)
- [ ] `symfony/monolog-bundle`: `^3.8.0` → Check Symfony 8.0 + Monolog 3.x compatibility

#### Third-Party Bundles (Verify Symfony 8.0 Compatibility)
- [ ] `friendsofsymfony/jsrouting-bundle`: `3.2.1` → Check Symfony 8.0 compatibility (latest: 3.6.1)
- [ ] `friendsofsymfony/rest-bundle`: `^3.4.0` → Check Symfony 8.0 compatibility (latest: 3.8.0)
- [ ] `liip/imagine-bundle`: `2.10.0` → Check Symfony 8.0 compatibility (latest: 2.17.1)
- [ ] `oneup/flysystem-bundle`: `^4.5.0` → Check Symfony 8.0 compatibility (latest: 4.14.1)
- [ ] `gedmo/doctrine-extensions`: `^3.2.0` → Check Symfony 8.0 + ORM 2.x compatibility (latest: 3.22.0)
- [ ] `symfony/acl-bundle`: `^2.1.0` → **WARNING**: May be deprecated in Symfony 8.0, check if still needed
- [ ] `akeneo/oauth-server-bundle`: `^v3.0.0` → Check Symfony 8.0 compatibility

#### Other Dependencies (Check Compatibility)
- [ ] `elasticsearch/elasticsearch`: `7.11.0` → Check Symfony 8.0 compatibility (latest: 9.2.0, major jump)
- [ ] `guzzlehttp/guzzle`: `^7.5.0` → Check Symfony 8.0 compatibility (latest: 7.10.0)
- [ ] `league/flysystem`: `^3.11.0` → Check Symfony 8.0 compatibility (latest: 3.30.2)
- [ ] `league/flysystem-aws-s3-v3`: `^3.10.3` → Check Symfony 8.0 compatibility (latest: 3.30.1)
- [ ] `dompdf/dompdf`: `2.0.3` → Check Symfony 8.0 compatibility (latest: 3.1.4, major jump)
- [ ] `openspout/openspout`: `^4.9.0` → Check Symfony 8.0 compatibility (latest: 5.2.1, major jump)
- [ ] `lcobucci/jwt`: `^4.2` → Check Symfony 8.0 compatibility (latest: 5.6.0, major jump)
- [ ] `ramsey/uuid`: `4.7.1` → Check Symfony 8.0 compatibility (likely OK)
- [ ] `ramsey/uuid-doctrine`: `^1.8` → Check Symfony 8.0 + ORM 2.x compatibility

#### Doctrine (Keep Current Versions - See `00-doctrine-orm-3-analysis.md`)
- [ ] `doctrine/orm`: `^2.9.0` → **KEEP** (compatible with Symfony 8.0, ORM 3 deferred to Phase 9)
- [ ] `doctrine/dbal`: `^2.13.4` → **KEEP** (compatible with Symfony 8.0, DBAL 4 deferred to Phase 9)
- [ ] `doctrine/persistence`: `^2.2.3` → Check Symfony 8.0 compatibility
- [ ] `doctrine/common`: `^3.2.0` → Check Symfony 8.0 compatibility
- [ ] `doctrine/annotations`: `^1.13.2` → Check if still needed (project uses YAML mappings)
- [ ] `doctrine/cache`: `^1.12.1` → Check if still needed
- [ ] `doctrine/collections`: `^v1.6.2` → Check Symfony 8.0 compatibility
- [ ] `doctrine/data-fixtures`: `^1.4.2` → Check Symfony 8.0 compatibility
- [ ] `doctrine/event-manager`: `^1.1.1` → Check Symfony 8.0 compatibility
- [ ] `doctrine/instantiator`: `^1.4.0` → Check Symfony 8.0 compatibility
- [ ] `doctrine/migrations`: `^3.1.4` → Check Symfony 8.0 compatibility
- [ ] `gedmo/doctrine-extensions`: `^3.2.0` → Check Symfony 8.0 compatibility

### Phase 6: PHP 8.4 → 8.5 Migration

#### Dependencies Requiring PHP 8.5+
- [ ] **Check each dependency** for PHP 8.5+ requirements
- [ ] **Update if incompatible** with PHP 8.5
- [ ] **Most dependencies** should work with PHP 8.5 if they work with PHP 8.4

**Note**: PHP 8.5 is very new, most dependencies may not explicitly support it yet but should work.

### Phase 7: Development Tools Migration

#### PHP Testing Tools
- [ ] `phpunit/phpunit`: `^9.5` → `^10.0` (requires PHP 8.1+, latest: 10.x)
- [ ] `phpspec/phpspec`: `^7.1.0` → Check PHP 8.5 compatibility (latest: 8.1.0, major jump)
- [ ] `phpspec/prophecy`: `^1.16.0` → Check PHP 8.5 compatibility (latest: 1.24.0)
- [ ] `behat/mink-selenium2-driver`: `^1.5.0` → Check PHP 8.5 compatibility (latest: 1.7.0)
- [ ] `friends-of-behat/mink`: `^1.9.0` → Check PHP 8.5 compatibility (latest: 1.11.0)
- [ ] `friends-of-behat/mink-browserkit-driver`: `^1.5.0` → Check PHP 8.5 compatibility (latest: 1.6.2)
- [ ] `friends-of-behat/mink-extension`: `^2.5.0` → Check PHP 8.5 compatibility (latest: 2.7.5)
- [ ] `friends-of-behat/symfony-extension`: `^2.4.0` → Check Symfony 8.0 + PHP 8.5 compatibility (latest: 2.6.2)
- [ ] `sensiolabs/behat-page-object-extension`: `^2.3.6` → Check PHP 8.5 compatibility
- [ ] `friends-of-phpspec/phpspec-code-coverage`: `^6.1.0` → Check PHP 8.5 compatibility (latest: 7.0.0, major jump)

#### PHP Code Quality Tools
- [ ] `phpstan/phpstan`: `^1.12` → Latest version (check PHP 8.5 support, latest: 2.1.33, major jump)
- [ ] `phpstan/phpstan-deprecation-rules`: `^1.1` → Latest version (check PHP 8.5 support)
- [ ] `phpstan/phpstan-symfony`: `^1.3` → Latest version (check Symfony 8.0 support)
- [ ] `phpstan/phpstan-webmozart-assert`: `^1.2` → Latest version (check PHP 8.5 support)
- [ ] `friendsofphp/php-cs-fixer`: `^3.13.0` → Latest version (check PHP 8.5 support, latest: 3.92.5)
- [ ] `squizlabs/php_codesniffer`: `3.*` → Check PHP 8.5 compatibility
- [ ] `vimeo/psalm`: `^5.1.0` → Latest version (check PHP 8.5 support)
- [ ] `rector/rector`: `^1.0` → Latest version (check PHP 8.5 and Symfony 8.0 support)
- [ ] `akeneo/php-coupling-detector`: `^0.8.1` → Latest version (latest: 0.8.3)
- [ ] `blackfire/php-sdk`: `^1.25` → Check PHP 8.5 compatibility (latest: 2.5.12, major jump)

#### JavaScript/TypeScript Testing Tools
- [ ] `jest`: `^26.4.2` → `^29.0` (requires Node.js 18+)
- [ ] `eslint`: `^6.5.1` → `^9.0` (requires Node.js 18+)
- [ ] `prettier`: `^2.1.1` → Latest version
- [ ] `cypress`: `^6.6.0` → Latest version
- [ ] `@types/react`: `^17.0.2` → `^19.0.0` (after React migration)
- [ ] `@types/react-dom`: `^17.0.2` → `^19.0.0` (after React migration)
- [ ] `typescript`: `^4.0.3` → `^5.6.0` (Phase 3)

### Phase 9: Doctrine ORM 3 Migration (OPTIONAL - Deferred)

#### Doctrine ORM 3 Dependencies (See `00-doctrine-orm-3-analysis.md`)
- [ ] `doctrine/orm`: `^2.9.0` → `^3.0` (requires DBAL 4, latest: 3.6.1)
- [ ] `doctrine/dbal`: `^2.13.4` → `^4.0` (breaking changes, latest: 4.4.1)
- [ ] `doctrine/doctrine-bundle`: `^2.5.5` → `^3.0` (latest: 3.2.2)
- [ ] `doctrine/persistence`: `^2.2.3` → Update for ORM 3 compatibility (latest: 4.1.1, major jump)
- [ ] `doctrine/common`: `^3.2.0` → May be removed (check ORM 3 requirements, latest: 3.5.0)
- [ ] `doctrine/cache`: `^1.12.1` → **REMOVE** (ORM 3 uses PSR-6, abandoned package)
- [ ] `doctrine/annotations`: `^1.13.2` → **REMOVE** (abandoned, project uses YAML mappings)
- [ ] `doctrine/collections`: `^v1.6.2` → Update for ORM 3 compatibility (latest: 2.5.1, major jump)
- [ ] `doctrine/data-fixtures`: `^1.4.2` → Update for ORM 3 compatibility (latest: 2.2.0, major jump)
- [ ] `doctrine/event-manager`: `^1.1.1` → Update for ORM 3 compatibility (latest: 2.0.1, major jump)
- [ ] `doctrine/instantiator`: `^1.4.0` → Update for ORM 3 compatibility (latest: 2.1.0, major jump)
- [ ] `doctrine/migrations`: `^3.1.4` → Update for ORM 3 compatibility (latest: 3.9.5)
- [ ] `doctrine/doctrine-migrations-bundle`: `^3.2.0` → Update for ORM 3 compatibility (latest: 4.0.0, major jump)
- [ ] `doctrine/doctrine-fixtures-bundle`: `^3.4.1` → Update for ORM 3 compatibility (latest: 4.3.1, major jump)

## Dependency Update Strategy

### Update Order Within Each Phase

1. **Core dependencies first** (Symfony components, Doctrine core)
2. **Bundles second** (Symfony bundles, third-party bundles)
3. **Supporting libraries third** (utilities, helpers)
4. **Development tools last** (testing, linting)

### Update Method

For each dependency:
1. **Check current version**: `composer show [package]`
2. **Check latest version**: `composer show [package] --latest`
3. **Check compatibility**: Read changelog, check PHP/Symfony requirements
4. **Dry-run update**: `composer update [package] --dry-run`
5. **Update**: `composer update [package] --with-all-dependencies`
6. **Test**: Run PHPStan, PHPUnit, Behat
7. **Commit**: Atomic commit per dependency or logical group

## Critical Dependencies Requiring Special Attention

### High Priority (Must Update Carefully)

1. **Symfony Components** (Phase 5)
   - All must be updated together
   - Breaking changes between major versions
   - Test thoroughly after each Symfony version jump

2. **Doctrine ORM/DBAL** (Phase 5 - keep 2.x, Phase 9 - upgrade to 3.x)
   - Keep ORM 2.x during Symfony migration
   - Defer ORM 3 to Phase 9 (see `00-doctrine-orm-3-analysis.md`)

3. **Twig** (Phase 5)
   - Symfony-dependent
   - Check Twig 4.0 compatibility with Symfony 8.0

4. **Security Components** (Phase 5)
   - `symfony/security-*` packages
   - Breaking changes likely
   - Test authentication/authorization thoroughly

### Medium Priority (Update with Caution)

1. **Monolog Bundle** (Phase 5)
   - Check Symfony 8.0 compatibility
   - May require Monolog 3.x

2. **Doctrine Extensions** (Phase 5)
   - `gedmo/doctrine-extensions`
   - Check Symfony 8.0 and ORM 2.x compatibility

3. **Behat/Mink** (Phase 7)
   - Testing tools
   - Check PHP 8.5 compatibility

### Low Priority (Can Update Anytime)

1. **Utility Libraries**
   - `webmozart/assert`
   - `ramsey/uuid`
   - Most independent libraries

2. **Cloud SDKs** (See `00-code-audit-dependencies.md`)
   - `google/cloud-pubsub`
   - `aws/aws-sdk-php`
   - Consider migration to open-source alternatives

## Automated Dependency Checking

### Commands to Check Dependencies

```bash
# Check all outdated packages
docker compose run --rm php composer outdated

# Check specific package
docker compose run --rm php composer show [package] --latest

# Dry-run update
docker compose run --rm php composer update [package] --dry-run

# Check PHP version requirements
docker compose run --rm php composer why-not php 8.4

# Check Symfony version requirements
docker compose run --rm php composer why-not symfony/symfony 8.0
```

## Action Items

### Before Starting Migration

- [ ] **Run `composer outdated`** - List all outdated packages
- [ ] **Document current versions** - Baseline for tracking
- [ ] **Check PHP 8.4 compatibility** - `composer why-not php 8.4`
- [ ] **Check Symfony 8.0 compatibility** - `composer why-not symfony/symfony 8.0`

### During Each Phase

- [ ] **Update dependencies** according to phase mapping
- [ ] **Test after each update** - PHPStan, PHPUnit, Behat
- [ ] **Document updates** - Track in appropriate phase tracking file
- [ ] **Commit atomically** - One dependency or logical group per commit

### After Migration

- [ ] **Verify all dependencies** are up to date
- [ ] **Check for security vulnerabilities** - `composer audit`
- [ ] **Document final versions** - Update tracking files

## Notes

- **Symfony components** must be updated together (same major version)
- **Doctrine ORM 2.x** is compatible with Symfony 8.0 (defer ORM 3 to Phase 9)
- **Most PHP 8.1 compatible packages** work with PHP 8.4/8.5
- **JavaScript/TypeScript dependencies** can be updated in parallel with PHP/Symfony
- **Development tools** should be updated last (Phase 7)
