# PHP Migration Tracking 8.1 → 8.4 → 8.5

**⚠️ IMPORTANT**: This migration is split into 2 phases with separate Git Flow branches:
- **Phase 2**: PHP 8.1 → 8.4 (MUST complete before Symfony 8.0)
  - **Git branch**: `feature/upgrade-2026-01-php-8.4`
- **Phase 6**: PHP 8.4 → 8.5 (MUST be done after Symfony 8.0 is stable)
  - **Git branch**: `feature/upgrade-2026-01-php-8.5`

Start date: 2026-01-14
End date: [To be completed]

## Git Flow Branch Information

### Phase 2 Branch: feature/upgrade-2026-01-php-8.4
- [x] Branch created: 2026-01-14 10:56:33 CET
- [x] Branch pushed to fork repository: 2026-01-14
- [ ] GitHub PR created: [To be completed] (Repository: https://github.com/gplanchat/pim-community-dev/)
- [ ] PR URL: [To be completed]
- [ ] Branch merged to master: [To be completed]
- [ ] Merge date: [To be completed]

### Phase 6 Branch: feature/upgrade-2026-01-php-8.5
- [ ] Branch created: [To be completed]
- [ ] Branch merged to develop: [To be completed]
- [ ] Merge date: [To be completed]
- [ ] Pull request URL: [To be completed]

## Current State
- Required PHP version: 8.1.* (from composer.json)
- Target PHP version Phase 2: 8.4.* (required for Symfony 8.0)
- Target PHP version Phase 6: 8.5.* (latest stable version, after Symfony 8.0)
- **Note**: Docker stack is used - system PHP version is irrelevant
- Rector configuration: ✅ Created and validated (2026-01-14)
- Dockerfile strategy: ✅ Dockerfile updated with multi-stage targets (base, dev, node) - contains ONLY current versions (PHP 8.1, Node 18)
- Docker Compose: ✅ Updated to use `Dockerfile` with build targets (dev for php/httpd, node for node)
- FrankenPHP migration: ⏳ Planned for Phase 6 (PHP 8.4 → 8.5) - see https://frankenphp.dev/fr/

## Phase 1: Preparation Status

### 1.1 Git Flow Setup
- [x] Branch created: 2026-01-14 10:56:33 CET
- [x] Initial state documented in tracking files: 2026-01-14
- [x] Branch creation documented: 2026-01-14

### 1.2 Dependency Installation
- [ ] Composer dependencies: ⚠️ To be installed via Docker when environment is ready
- [ ] Yarn dependencies: ⚠️ To be installed via Docker when environment is ready
- [ ] Initial tests verification: ⚠️ To be executed via Docker when environment is ready

### 1.3 Rector Configuration
- [x] Rector.php file created: 2026-01-14
- [x] Configuration validated (PHP syntax): 2026-01-14
- [x] Paths configured: src/, tests/, upgrades/, components/
- [x] Skip paths configured: std-build/migration, vendor/, var/, front-packages/, frontend/
- [ ] Rector dry-run test: ⚠️ To be executed via Docker when environment is ready

**Note**: Docker stack is required for full validation. Configuration file syntax is valid and ready for use.

## Applied Rector Rules

### Rule 1: PHP_82 - PHP 8.1 → 8.2
- [x] Dockerfile updated for PHP 8.2: 2026-01-14
- [x] Docker images rebuilt: 2026-01-14
- [x] PHP version verified in container: PHP 8.2.30 confirmed
- [x] Application date: 2026-01-14
- [x] Dry-run executed: 2026-01-14
- [x] Dry-run review: No changes needed - codebase already compatible with PHP 8.2
- [x] Modified files: None (code already compatible)
- [x] Tests executed: PHPStan executed (7427 pre-existing errors, none related to PHP 8.2 migration)
- [x] Test results: No new errors introduced by PHP 8.2 migration
- [x] Issues encountered: None
- [x] Solutions applied: N/A - no changes needed
- **Status**: ✅ Completed - 2026-01-14
- **Notes**: 
  - Rector PHP_82 dry-run found no changes needed
  - Codebase was already compatible with PHP 8.2
  - PHPStan errors are pre-existing and not related to PHP 8.2 migration
  - Dockerfile successfully updated and images rebuilt

### Rule 2: PHP_83 - PHP 8.2 → 8.3
- [ ] Dockerfile updated for PHP 8.3: [To be completed]
- [ ] Docker images rebuilt: [To be completed]
- [ ] PHP version verified in container: [To be completed]
- [ ] Application date: [To be completed]
- [ ] Dry-run executed: [To be completed]
- [ ] Dry-run review: [To be completed]
- [ ] Modified files: [To be completed]
- [ ] Tests executed: [To be completed]
- [ ] Test results: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]

### Rule 3: PHP_84 - PHP 8.3 → 8.4 (REQUIRED before Symfony 8.0)
- [ ] Dockerfile updated for PHP 8.4: [To be completed]
- [ ] Docker images rebuilt: [To be completed]
- [ ] PHP version verified in container: [To be completed]
- [ ] Application date: [To be completed]
- [ ] Dry-run executed: [To be completed]
- [ ] Dry-run review: [To be completed]
- [ ] Modified files: [To be completed]
- [ ] Tests executed: [To be completed]
- [ ] Test results: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]
- [ ] **PHP 8.4.0+ verified**: Check `composer.json` and Docker container

## Phase 6: PHP 8.4 → 8.5 Migration + FrankenPHP (AFTER Symfony 8.0)

**⚠️ CRITICAL**: This phase can ONLY be started after Symfony 8.0 migration (Phase 5) is completed and stable.

**🎯 SPECIAL**: This phase includes migration to **FrankenPHP** (https://frankenphp.dev/fr/) - a modern PHP application server written in Go, using Caddy as web server. FrankenPHP supports PHP 8.2+ and offers better performance than PHP-FPM.

### Prerequisites Check
- [ ] Symfony 8.0 migration completed: [To be completed]
- [ ] Symfony 8.0 is stable: [To be completed]
- [ ] All Symfony 8.0 tests passing: [To be completed]
- [ ] Ready to proceed to PHP 8.5: [Yes/No]

### Step 1: Update Dockerfile for PHP 8.5
- [ ] Dockerfile updated for PHP 8.5: [To be completed]
- [ ] Docker images rebuilt: [To be completed]
- [ ] PHP version verified in container: [To be completed]

### Step 2: Apply PHP_85 Rector Rule
- [ ] Application date: [To be completed]
- [ ] Dry-run executed: [To be completed]
- [ ] Dry-run review: [To be completed]
- [ ] Modified files: [To be completed]
- [ ] Tests executed: [To be completed]
- [ ] Test results: [To be completed]
- [ ] Symfony 8.0 compatibility verified: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]

### Step 3: Migrate to FrankenPHP
- [ ] Research FrankenPHP compatibility with Akeneo PIM: [To be completed]
- [ ] Update Dockerfile to use FrankenPHP image: [To be completed]
  - Base image: `dunglas/frankenphp` (official image)
  - PHP version: 8.5 (when available) or 8.4
- [ ] Update docker-compose.yml for FrankenPHP: [To be completed]
- [ ] Configure Caddyfile if needed: [To be completed]
- [ ] Test FrankenPHP worker mode (optional): [To be completed]
- [ ] Verify HTTP/2 and HTTP/3 support: [To be completed]
- [ ] Performance testing: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]

**FrankenPHP Benefits**:
- ✅ Native HTTP/2 and HTTP/3 support
- ✅ Automatic HTTPS certificates (Let's Encrypt)
- ✅ Worker mode (3.5x faster than FPM for API Platform)
- ✅ Built-in compression (Brotli, Zstandard, Gzip)
- ✅ Single binary, no external services needed
- ✅ Compatible with Symfony, Laravel, API Platform

### Rule 5: TypedPropertyRector
- [ ] Application date: [To be completed]
- [ ] Modified files: [To be completed]
- [ ] Tests executed: [To be completed]
- [ ] Test results: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]

### Rule 6: ReadOnlyPropertyRector
- [ ] Application date: [To be completed]
- [ ] Modified files: [To be completed]
- [ ] Tests executed: [To be completed]
- [ ] Test results: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]

### Rule 7: OverrideAttributeRector
- [ ] Application date: [To be completed]
- [ ] Modified files: [To be completed]
- [ ] Tests executed: [To be completed]
- [ ] Test results: [To be completed]
- [ ] Issues encountered: [To be completed]
- [ ] Solutions applied: [To be completed]

## Dependency Updates

### Updated Dependencies
- [ ] List of updated dependencies: [To be completed]
- [ ] Identified breaking changes: [To be completed]
- [ ] Required adaptations: [To be completed]

## Tests

### PHPStan (Static Analysis - Execute First)
- [ ] Date: [To be completed]
- [ ] Result: [To be completed]
- [ ] Errors: [To be completed]

### PHPUnit (Unit Tests - Execute After PHPStan)
- [ ] Date: [To be completed]
- [ ] Result: [To be completed]
- [ ] Errors: [To be completed]

### Behat
- [ ] Date: [To be completed]
- [ ] Result: [To be completed]
- [ ] Errors: [To be completed]

### PHPStan
- [ ] Date: [To be completed]
- [ ] Result: [To be completed]
- [ ] Errors: [To be completed]

### PHP-CS-Fixer
- [ ] Date: [To be completed]
- [ ] Result: [To be completed]
- [ ] Errors: [To be completed]

## Issues Encountered

### Issue 1: [Title]
- Description: [To be completed]
- Solution: [To be completed]
- Resolution date: [To be completed]

## Notes
[Additional notes]
