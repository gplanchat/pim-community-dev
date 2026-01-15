# Detailed Action Plan

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Phase 0: Pre-Migration Validation ⚠️ MUST COMPLETE FIRST

**⚠️ CRITICAL**: This phase must be completed before starting any migration work.

**Objective**: Ensure codebase is fully functional or track all errors before migration starts.

- [ ] **Read `00-pre-migration-validation.md`**: Complete guide for Phase 0
- [ ] **Read `00-mikado-method-guide.md`**: Understand Mikado Method for complex dependencies
- [ ] **Read `12-mikado-graph-template.md`**: Understand Mikado graph structure
- [ ] **Run complete validation suite**: PHPStan, PHPUnit, Behat, JS/TS tests
- [ ] **Categorize errors**: Simple fixes (Category A) vs Medium (Category B) vs Complex (Category C)
- [ ] **Fix Category A errors**: Simple errors that can be fixed in < 30 minutes
- [ ] **Create error tracking**: Document all errors in `10-error-tracking.md`
- [ ] **Establish baseline**: Document current state metrics
- [ ] **Merge simple fixes**: Create PR and merge to master before Phase 1

**See `00-pre-migration-validation.md` for detailed steps.**

**Do NOT proceed to Phase 1 until Phase 0 is complete.**

---

## Phase 1: Preparation

### 1.0 Code Audit and Dependency Analysis
- [ ] **Review Phase 0 results**: Check `10-error-tracking.md` for remaining errors
- [ ] **Review `00-code-audit-dependencies.md`**: Understand missing dependencies and cloud services
- [ ] **Plan cloud service migrations**: Decide on open-source alternatives (MongoDB/PostgreSQL for Firestore, RabbitMQ/Redis for PubSub)
- [ ] **Add missing dependencies** OR plan migration to open-source alternatives

### 1.1 Git Flow Setup
- [ ] Verify you are on `master` branch: `git checkout master`
- [ ] Pull latest changes: `git pull origin master`
- [ ] Verify all tests pass on master branch
- [ ] Document initial state in tracking files
- [ ] **Create Phase 2 branch**: `git checkout -b feature/upgrade-2026-01-php-8.4`
- [ ] Document branch creation in `04-php-tracking.md`

### 1.2 Dependency Installation
- [ ] Install Composer dependencies: `composer install`
- [ ] Install Yarn dependencies: `yarn install`
- [ ] Verify everything works: `yarn test && vendor/bin/phpstan analyse && vendor/bin/phpunit`

### 1.3 Rector Configuration
- [ ] Create `rector.php` file at project root
- [ ] Configure Rector for PHP and Symfony migrations
- [ ] Test configuration: `vendor/bin/rector --dry-run`

## Phase 2: PHP 8.1 → 8.4 Migration (BEFORE Symfony 8.0)

**⚠️ CRITICAL**: This phase MUST be completed before starting Symfony 8.0 migration. Symfony 8.0 requires PHP 8.4.0+.

### 2.1 Update Required PHP Version to 8.4
- [ ] Modify `composer.json`: `"php": "^8.4"`
- [ ] Run: `composer update --dry-run`
- [ ] Check for potential conflicts
- [ ] **DO NOT** set PHP to 8.5 yet (wait until after Symfony 8.0)

### 2.2 Apply Rector PHP Rules (in order)
**Rule 1: PHP_82 - PHP 8.1 → 8.2**
- [ ] **Update Dockerfile**: Change PHP 8.1 to PHP 8.2 (see `DOCKERFILE-MIGRATION.md`)
- [ ] **Commit Dockerfile changes**: `git commit -m "chore(docker): update Dockerfile for PHP 8.2"`
- [ ] **Rebuild Docker images**: `docker compose build php httpd`
- [ ] **Verify PHP version**: `docker compose run --rm php php -v` (should show 8.2)
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_82 --dry-run`
- [ ] Review proposed changes
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_82`
- [ ] Run tests: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] Document in `04-php-tracking.md`

**Rule 2: PHP_83 - PHP 8.2 → 8.3**
- [ ] **Update Dockerfile**: Change PHP 8.2 to PHP 8.3 (see `DOCKERFILE-MIGRATION.md`)
- [ ] **Commit Dockerfile changes**: `git commit -m "chore(docker): update Dockerfile for PHP 8.3"`
- [ ] **Rebuild Docker images**: `docker compose build php httpd`
- [ ] **Verify PHP version**: `docker compose run --rm php php -v` (should show 8.3)
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_83 --dry-run`
- [ ] Review proposed changes
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_83`
- [ ] Run validations: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] **If PHPStan fails**: Fix errors one by one, commit each fix separately
- [ ] **If tests fail**: Fix tests one by one, commit each fix separately
- [ ] **When all pass**: Commit Rector changes atomically
- [ ] Document in `04-php-tracking.md`

**Rule 3: PHP_84 - PHP 8.3 → 8.4 (REQUIRED before Symfony 8.0)**
- [ ] **Update Dockerfile**: Change PHP 8.3 to PHP 8.4 (see `DOCKERFILE-MIGRATION.md`)
- [ ] **Commit Dockerfile changes**: `git commit -m "chore(docker): update Dockerfile for PHP 8.4"`
- [ ] **Rebuild Docker images**: `docker compose build php httpd`
- [ ] **Verify PHP version**: `docker compose run --rm php php -v` (should show 8.4)
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_84 --dry-run`
- [ ] Review proposed changes
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_84`
- [ ] Run validations: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] **If PHPStan fails**: Fix errors one by one, commit each fix separately
- [ ] **If tests fail**: Fix tests one by one, commit each fix separately
- [ ] **When all pass**: Commit Rector changes atomically
- [ ] Document in `04-php-tracking.md`
- [ ] **Verify PHP 8.4.0+ is working**: Check `composer.json` and Docker container (`docker compose run --rm php php -v`)

### 2.3 Update PHP Dependencies (PHP 8.4 compatible)
- [ ] Run: `composer update`
- [ ] Check breaking changes in dependencies
- [ ] Verify all dependencies are compatible with PHP 8.4
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `04-php-tracking.md`

### 2.4 Final PHP 8.4 Verification
- [ ] Run PHPStan: `vendor/bin/phpstan analyse` (static analysis - validates code before runtime tests)
- [ ] Run PHP-CS-Fixer: `vendor/bin/php-cs-fixer fix`
- [ ] Run all tests: `vendor/bin/phpunit && vendor/bin/behat` (after PHPStan passes)
- [ ] **Verify PHP version**: Confirm PHP 8.4.0+ in `composer.json`
- [ ] Document in `04-php-tracking.md`
- [ ] **Ready for Symfony 8.0 migration**: PHP 8.4.0+ requirement met

### 2.5 Phase 2 Branch Completion
- [ ] **Verify all commits are atomic**: Each commit should pass validations independently
- [ ] **No uncommitted changes**: All changes should be committed atomically
- [ ] **Push branch to fork repository**: `git push origin feature/upgrade-2026-01-php-8.4`
- [ ] **Read `00-github-automation-guide.md`**: Understand GitHub automation workflow
- [ ] **Create phase issue** (using GitHub MCP/CLI/API):
  - Title: `[Phase 2] PHP 8.1 → 8.4 Migration`
  - Use phase issue template from `00-github-automation-guide.md`
  - Labels: `migration`, `phase-2`, `php`
  - Milestone: `Upgrade 2026-01`
  - Store issue number in `11-status-report.md`
- [ ] **Create phase PR** (using GitHub MCP/CLI/API):
  - Title: `feat(upgrade): Phase 2 - PHP 8.1 → 8.4 Migration`
  - Base: `master`
  - Head: `feature/upgrade-2026-01-php-8.4`
  - Start as draft PR
  - Link to phase issue (use `Closes #XXX` or `Related to #XXX`)
  - Labels: `migration`, `phase-2`, `php`, `work-in-progress`
  - Include phase objectives, prerequisites, and checklist in description
  - Store PR number in `11-status-report.md`
- [ ] **Update PR description** with final status, test results, and completion checklist
- [ ] **Update PR status** (using GitHub MCP/CLI/API):
  - Remove "work-in-progress" label
  - Add "ready-for-review" label
  - Change PR from draft to ready
- [ ] **Request review** (if required): Add reviewers to PR using GitHub MCP/CLI/API
- [ ] **After approval**: Merge PR to master using GitHub MCP/CLI/API
  - Use merge method: "merge" (to preserve history)
  - Commit message: "feat(upgrade): Complete Phase 2 - PHP 8.1 → 8.4 Migration"
- [ ] **Close phase issue**: Update issue with completion summary, then close using GitHub MCP/CLI/API
- [ ] **Close related issues**: Close any problem issues that were resolved
- [ ] **After merge**: `git checkout master && git pull origin master`
- [ ] Delete Phase 2 branch: `git branch -d feature/upgrade-2026-01-php-8.4`
- [ ] Delete remote branch: `git push origin --delete feature/upgrade-2026-01-php-8.4`
- [ ] Document merge completion in `04-php-tracking.md`

## Phase 3: TypeScript 4.0 → 5.6 Migration

### 3.1 TypeScript Update
- [ ] Modify `package.json`: `"typescript": "^5.6.0"`
- [ ] Run: `yarn install`
- [ ] Verify: `yarn tsc --noEmit`

### 3.2 TypeScript Error Correction
- [ ] List errors: `yarn tsc --noEmit > ts-errors.log`
- [ ] Fix errors one by one
- [ ] Run tests after each correction: `yarn unit`
- [ ] Document in `05-typescript-tracking.md`

### 3.3 TypeScript Configuration Update
- [ ] Update `tsconfig.json` if necessary
- [ ] Add new TypeScript 5.6 options
- [ ] Verify: `yarn tsc --noEmit`
- [ ] Run tests: `yarn unit && yarn integration`
- [ ] Document in `05-typescript-tracking.md`

### 3.4 Final TypeScript Verification
- [ ] Run build: `yarn webpack-dev`
- [ ] Run all tests: `yarn test`
- [ ] Document in `05-typescript-tracking.md`

## Phase 4: React 17 → 19 Migration

### 4.1 React 17 → 18 Migration
- [ ] Modify `package.json`: `"react": "^18.0.0", "react-dom": "^18.0.0"`
- [ ] Run: `yarn install`
- [ ] Apply react-codemod: `npx react-codemod react-18-upgrade`
- [ ] Run tests: `yarn unit && yarn integration`
- [ ] Document in `06-react-tracking.md`

### 4.2 React 18 Manual Corrections
- [ ] Update deprecated hooks
- [ ] Adapt code for Concurrent Rendering
- [ ] Update tests if necessary
- [ ] Run tests after each change: `yarn unit`
- [ ] Document in `06-react-tracking.md`

### 4.3 React 18 → 19 Migration
- [ ] Modify `package.json`: `"react": "^19.2.0", "react-dom": "^19.2.0"`
- [ ] Run: `yarn install`
- [ ] Apply react-codemod if available: `npx react-codemod react-19-upgrade`
- [ ] Run tests: `yarn unit && yarn integration`
- [ ] Document in `06-react-tracking.md`

### 4.4 React 19 Manual Corrections
- [ ] Update to new APIs (Actions, useFormStatus, etc.)
- [ ] Adapt code for new features
- [ ] Update tests if necessary
- [ ] Run tests after each change: `yarn unit`
- [ ] Document in `06-react-tracking.md`

### 4.5 React Dependencies Update
- [ ] Check react-redux compatibility with React 19
- [ ] Check react-router-dom compatibility with React 19
- [ ] Check react-query compatibility with React 19
- [ ] Check styled-components compatibility with React 19
- [ ] Update dependencies if necessary
- [ ] Run tests: `yarn test`
- [ ] Document in `06-react-tracking.md`

### 4.6 Final React Verification
- [ ] Run build: `yarn webpack-dev`
- [ ] Run all tests: `yarn test`
- [ ] Run E2E tests: `yarn test:e2e:run`
- [ ] Document in `06-react-tracking.md`

## Phase 5: Symfony 5.4 → 8.0 Migration

**⚠️ CRITICAL PREREQUISITE**: 
- PHP 8.4.0+ MUST be completed (Phase 2)
- Phase 2 branch (`feature/upgrade-2026-01-php-8.4`) MUST be merged to develop
- Verify PHP version in `composer.json` is `^8.4` before starting

### 5.0 Git Flow Branch Setup
- [ ] Verify you are on `develop` branch: `git checkout develop`
- [ ] Verify Phase 2 branch is merged: Check git log for Phase 2 commits
- [ ] Pull latest changes: `git pull origin develop`
- [ ] **Create Phase 5 branch**: `git checkout -b feature/upgrade-2026-01-symfony-8.0`
- [ ] Document branch creation in `07-symfony-tracking.md`

**Migration order**: 5.4 → 6.0 → 6.4 → 7.0 → 8.0 (sequential, each step requires specific PHP version)

**⚠️ NOTE**: If encountering complex dependencies during Symfony migration, use Mikado Method:
- [ ] **Create Mikado graph**: `12-mikado-graph-symfony-8.0.md` (use `12-mikado-graph-template.md`)
- [ ] **Set goal**: Migrate Symfony 5.4 → 8.0
- [ ] **Document dependencies** as they are discovered
- [ ] **Resolve dependencies iteratively** using Mikado Method workflow (see `00-mikado-method-guide.md`)
- [ ] **Update graph** as dependencies are resolved
- [ ] **Commit each dependency resolution** atomically

### 5.1 Symfony 5.4 → 6.0 Migration
**Rule 1: SYMFONY_60 - Replace annotations with attributes**
- [ ] Apply: `vendor/bin/rector process --set=SYMFONY_60 --dry-run`
- [ ] Review proposed changes
- [ ] Apply: `vendor/bin/rector process --set=SYMFONY_60`
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

**Rule 2: SYMFONY_60 - Route migration**
- [ ] Apply route migration rules
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

**Rule 3: SYMFONY_60 - Event migration**
- [ ] Apply event migration rules
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

**Rule 4: SYMFONY_60 - Service migration**
- [ ] Apply service migration rules
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

**Rule 5: SYMFONY_60 - Doctrine code migration (if applicable)**
- [ ] **Note**: This project uses YAML mappings (`.orm.yml`), not PHP annotations
- [ ] **No database schema migration needed**: Doctrine ORM/DBAL remains compatible across Symfony versions
- [ ] **If using annotations elsewhere**: Apply Doctrine annotation → attribute migration rules
- [ ] Run tests: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

### 5.2 Symfony 6.0 Dependencies Update
- [ ] Modify `composer.json`: Update all Symfony dependencies to ^6.0
- [ ] Run: `composer update symfony/* --with-all-dependencies`
- [ ] Check breaking changes
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

### 5.3 Symfony 6.0 → 6.4 Migration
- [ ] Apply: `vendor/bin/rector process --set=SYMFONY_64`
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Modify `composer.json`: Update to ^6.4
- [ ] Run: `composer update symfony/* --with-all-dependencies`
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

### 5.4 Symfony 6.4 → 7.0 Migration
- [ ] Apply: `vendor/bin/rector process --set=SYMFONY_70`
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Modify `composer.json`: Update to ^7.0
- [ ] Run: `composer update symfony/* --with-all-dependencies`
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

### 5.5 Symfony 7.0 → 8.0 Migration
- [ ] **Verify PHP 8.4+**: Check `composer.json` shows `"php": "^8.4"` (Docker handles version, not system)
- [ ] **Verify PHP 8.4.0+ requirement**: Confirm in Docker container
- [ ] Apply: `vendor/bin/rector process --set=SYMFONY_80` (if available)
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Modify `composer.json`: Update to ^8.0
- [ ] Run: `composer update symfony/* --with-all-dependencies`
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`
- [ ] **Symfony 8.0 migration complete** - Ready for PHP 8.5 migration (Phase 6)

### 5.6 Third-Party Symfony Bundles Update
- [ ] **Read `00-dependencies-phase-mapping.md`**: Complete dependency phase mapping guide
- [ ] **Doctrine ORM**: Keep Doctrine ORM 2.x (compatible with Symfony 8.0, see `00-doctrine-orm-3-analysis.md`)
  - [ ] Verify `doctrine/orm: ^2.9.0` works with Symfony 8.0
  - [ ] Verify `doctrine/dbal: ^2.13.4` works with Symfony 8.0
  - [ ] Verify `doctrine/doctrine-bundle: ^2.5.5` compatible with Symfony 8.0
  - [ ] **Note**: Doctrine ORM 3 migration deferred to Phase 9 (see `00-doctrine-orm-3-analysis.md`)
- [ ] **Monolog**: Update to Monolog 3.x (likely required for Symfony 8.0)
  - [ ] Update `monolog/monolog`: `^2.8.0` → `^3.0`
  - [ ] Update `symfony/monolog-bundle`: Check compatibility with Monolog 3.x
- [ ] **Twig**: Update for Symfony 8.0 compatibility
  - [ ] Update `twig/twig`: `^3.3.3` → Check Symfony 8.0 requirement (likely `^3.8` or `^4.0`)
- [ ] **Third-Party Bundles** (see `00-dependencies-phase-mapping.md` for details):
  - [ ] `friendsofsymfony/jsrouting-bundle`: `3.2.1` → Check Symfony 8.0 compatibility (latest: 3.6.1)
  - [ ] `friendsofsymfony/rest-bundle`: `^3.4.0` → Check Symfony 8.0 compatibility (latest: 3.8.0)
  - [ ] `liip/imagine-bundle`: `2.10.0` → Check Symfony 8.0 compatibility (latest: 2.17.1)
  - [ ] `oneup/flysystem-bundle`: `^4.5.0` → Check Symfony 8.0 compatibility (latest: 4.14.1)
  - [ ] `gedmo/doctrine-extensions`: `^3.2.0` → Check Symfony 8.0 + ORM 2.x compatibility (latest: 3.22.0)
  - [ ] `symfony/acl-bundle`: `^2.1.0` → **WARNING**: May be deprecated, check if still needed
  - [ ] `akeneo/oauth-server-bundle`: `^v3.0.0` → Check Symfony 8.0 compatibility
- [ ] **Other Dependencies** (check compatibility):
  - [ ] `elasticsearch/elasticsearch`: `7.11.0` → Check Symfony 8.0 compatibility (latest: 9.2.0, major jump)
  - [ ] `guzzlehttp/guzzle`: `^7.5.0` → Update to latest (latest: 7.10.0)
  - [ ] `league/flysystem`: `^3.11.0` → Update to latest (latest: 3.30.2)
  - [ ] `dompdf/dompdf`: `2.0.3` → Check Symfony 8.0 compatibility (latest: 3.1.4, major jump)
  - [ ] `openspout/openspout`: `^4.9.0` → Check Symfony 8.0 compatibility (latest: 5.2.1, major jump)
  - [ ] `lcobucci/jwt`: `^4.2` → Check Symfony 8.0 compatibility (latest: 5.6.0, major jump)
- [ ] **Run dependency check**: `docker compose run --rm php composer outdated`
- [ ] **Update dependencies**: `docker compose run --rm php composer update [package] --with-all-dependencies`
- [ ] **Test after each update**: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] **Commit atomically**: One dependency or logical group per commit
- [ ] Update bundles if necessary
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

### 5.7 Final Symfony Verification
- [ ] Run PHPStan: `vendor/bin/phpstan analyse`
- [ ] Run PHP-CS-Fixer: `vendor/bin/php-cs-fixer fix`
- [ ] Run all tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Document in `07-symfony-tracking.md`

### 5.8 Phase 5 Branch Completion
- [ ] Commit all changes: `git add . && git commit -m "feat(upgrade): complete Symfony 5.4 → 8.0 migration"`
- [ ] Create pull request: `feature/upgrade-2026-01-symfony-8.0` → `develop`
- [ ] Code review and approval
- [ ] Merge to develop: `git checkout develop && git merge feature/upgrade-2026-01-symfony-8.0`
- [ ] Delete Phase 5 branch: `git branch -d feature/upgrade-2026-01-symfony-8.0`
- [ ] Document merge completion in `07-symfony-tracking.md`
- [ ] **Symfony 8.0 migration complete** - Ready for PHP 8.5 migration (Phase 6)

## Phase 6: PHP 8.4 → 8.5 Migration + FrankenPHP (AFTER Symfony 8.0)

**⚠️ CRITICAL PREREQUISITE**: 
- Symfony 8.0 MUST be completed and stable (Phase 5)
- Phase 5 branch (`feature/upgrade-2026-01-symfony-8.0`) MUST be merged to master
- Only proceed after Symfony 8.0 is fully working

**🎯 SPECIAL**: This phase includes migration to **FrankenPHP** (https://frankenphp.dev/fr/) - a modern PHP application server written in Go, using Caddy as web server. FrankenPHP supports PHP 8.2+ and offers better performance than PHP-FPM.

### 6.0 Git Flow Branch Setup
- [ ] Verify you are on `master` branch: `git checkout master`
- [ ] Verify Phase 5 branch is merged: Check git log for Phase 5 commits
- [ ] Pull latest changes: `git pull origin master`
- [ ] **Create Phase 6 branch**: `git checkout -b feature/upgrade-2026-01-php-8.5`
- [ ] Document branch creation in `04-php-tracking.md`

### 6.1 Verify Symfony 8.0 Stability
- [ ] Confirm Symfony 8.0 is stable and all tests pass
- [ ] Verify no critical issues with Symfony 8.0
- [ ] Document readiness for PHP 8.5 in `04-php-tracking.md`

### 6.2 Update Dockerfile for PHP 8.5
- [ ] **Update Dockerfile**: Change PHP 8.4 to PHP 8.5 (see `DOCKERFILE-MIGRATION.md`)
- [ ] **Commit Dockerfile changes**: `git commit -m "chore(docker): update Dockerfile for PHP 8.5"`
- [ ] **Rebuild Docker images**: `docker compose build php httpd`
- [ ] **Verify PHP version**: `docker compose run --rm php php -v` (should show 8.5)

### 6.3 Update Required PHP Version to 8.5
- [ ] Modify `composer.json`: `"php": "^8.5"`
- [ ] Run: `composer update --dry-run`
- [ ] Check for potential conflicts with Symfony 8.0
- [ ] Verify Symfony 8.0 compatibility with PHP 8.5

### 6.4 Apply Rector PHP 8.5 Rules
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_85 --dry-run`
- [ ] Review proposed changes
- [ ] Apply: `docker compose run --rm php vendor/bin/rector process --set=PHP_85`
- [ ] Run tests: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] Document in `04-php-tracking.md`

### 6.5 Verify Symfony 8.0 Compatibility with PHP 8.5
- [ ] Run all Symfony tests: `docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] Verify no regressions introduced
- [ ] Check Symfony 8.0 bundles compatibility
- [ ] Document in `04-php-tracking.md`

### 6.6 Migrate to FrankenPHP
**🎯 NEW**: Migrate from PHP-FPM to FrankenPHP for better performance.

- [ ] **Research FrankenPHP compatibility**: Verify Akeneo PIM compatibility with FrankenPHP
- [ ] **Update Dockerfile**: Replace PHP-FPM with FrankenPHP base image (`dunglas/frankenphp`)
- [ ] **Configure Caddyfile**: Create/update Caddyfile if needed for FrankenPHP
- [ ] **Update docker-compose.yml**: Adjust configuration for FrankenPHP
- [ ] **Test FrankenPHP worker mode** (optional but recommended):
  - Configure worker mode for Symfony/API Platform
  - Test performance improvements
- [ ] **Verify HTTP/2 and HTTP/3 support**: Test modern HTTP protocols
- [ ] **Performance testing**: Compare FrankenPHP vs PHP-FPM performance
- [ ] **Run all tests**: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] **Document migration**: Update `04-php-tracking.md` with FrankenPHP migration details
- [ ] **Commit changes**: `git commit -m "feat(docker): migrate to FrankenPHP for PHP 8.5"`

**FrankenPHP Resources**:
- Official website: https://frankenphp.dev/fr/
- Docker image: `dunglas/frankenphp`
- Documentation: https://frankenphp.dev/fr/docs/
- Benefits: HTTP/2 & HTTP/3, automatic HTTPS, worker mode (3.5x faster), built-in compression

### 6.7 Final PHP 8.5 + FrankenPHP Verification
- [ ] Run PHPStan: `docker compose run --rm php vendor/bin/phpstan analyse`
- [ ] Run PHP-CS-Fixer: `docker compose run --rm php vendor/bin/php-cs-fixer fix`
- [ ] Run all tests: `docker compose run --rm php vendor/bin/phpstan analyse && docker compose run --rm php vendor/bin/phpunit && docker compose run --rm php vendor/bin/behat`
- [ ] Verify PHP version: Confirm PHP 8.5 in `composer.json` and Docker container
- [ ] Verify FrankenPHP is running: Check container logs and HTTP/2/HTTP/3 support
- [ ] Document in `04-php-tracking.md`

## Phase 7: Development Tools Migration

### 7.1 PHPUnit 9 → 10 Migration
- [ ] Modify `composer.json`: `"phpunit/phpunit": "^10.0"`
- [ ] Run: `composer update phpunit/phpunit --with-all-dependencies`
- [ ] Adapt `phpunit.xml.dist` if necessary
- [ ] Run tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit`
- [ ] Document in `08-tools-tracking.md`

### 7.2 Jest 26 → 29+ Migration
- [ ] Modify `package.json`: `"jest": "^29.0.0"`
- [ ] Run: `yarn install`
- [ ] Adapt Jest configurations if necessary
- [ ] Run tests: `yarn unit && yarn integration`
- [ ] Document in `08-tools-tracking.md`

### 7.3 ESLint 6 → 9 Migration
- [ ] Modify `package.json`: `"eslint": "^9.0.0"`
- [ ] Run: `yarn install`
- [ ] Migrate to ESLint flat configuration
- [ ] Adapt `.eslintrc` to new format
- [ ] Run: `yarn lint`
- [ ] Document in `08-tools-tracking.md`

### 7.4 Other Tools Update
- [ ] Update Prettier if necessary
- [ ] Update Cypress if necessary
- [ ] Update other development tools
- [ ] Run tests: `yarn test && vendor/bin/phpstan analyse && vendor/bin/phpunit`
- [ ] Document in `08-tools-tracking.md`

## Phase 8: Final Tests and Validation

### 8.0 Instance Migration Preparation

**⚠️ CRITICAL**: Before deployment, prepare instance migration procedures.

- [ ] **Read `00-instance-migration-guide.md`**: Understand migration procedures
- [ ] **Create unified migration script**: `bin/migrate-instance.sh`
  - [ ] Include pre-flight checks
  - [ ] Include database migration steps
  - [ ] Include cache clearing steps
  - [ ] Include configuration update steps
  - [ ] Include validation steps
  - [ ] Include rollback capability
  - [ ] Test script on staging environment
- [ ] **Document manual procedures**: If automation not possible, document in `00-instance-migration-guide.md`
  - [ ] Backup procedures
  - [ ] Database migration steps
  - [ ] Cache clearing steps
  - [ ] Configuration update steps
  - [ ] File system update steps
  - [ ] Validation steps
  - [ ] Rollback procedures
- [ ] **Test migration procedures**: On staging environment before production
- [ ] **Document any issues**: Update `00-instance-migration-guide.md` with findings
- [ ] **Commit migration script**: `git commit -m "feat(migration): add unified instance migration script"`
- [ ] **Commit documentation**: `git commit -m "docs(migration): add instance migration procedures"`

### 8.1 Complete Tests
- [ ] Run all PHP tests: `vendor/bin/phpstan analyse && vendor/bin/phpunit && vendor/bin/behat`
- [ ] Run all JS/TS tests: `yarn test`
- [ ] Run E2E tests: `yarn test:e2e:run`
- [ ] Verify build: `yarn webpack-dev`
- [ ] Document in `09-final-validation.md`

### 8.2 Static Analysis
- [ ] Run PHPStan: `vendor/bin/phpstan analyse`
- [ ] Run PHP-CS-Fixer: `vendor/bin/php-cs-fixer fix --dry-run`
- [ ] Run ESLint: `yarn lint`
- [ ] Run Prettier: `yarn prettier:check`
- [ ] Document in `09-final-validation.md`

### 8.3 Documentation
- [ ] Update README if necessary
- [ ] Update change documentation
- [ ] Create CHANGELOG file for this migration
- [ ] Document in `09-final-validation.md`

### 8.4 Code Review
- [ ] Perform complete code review
- [ ] Verify all changes are documented
- [ ] Verify all tests pass
- [ ] Document in `09-final-validation.md`

## Phase 10: Deployment

### 10.1 Deployment Preparation
- [ ] Create version tag if necessary
- [ ] Prepare release notes
- [ ] Verify everything is ready for deployment

### 10.2 Merge with Main Branch
- [ ] Merge upgrade-2026-01 branch with main branch
- [ ] Resolve any conflicts
- [ ] Verify everything works after merge

### 10.3 Deployment
- [ ] Deploy to staging environment
- [ ] Verify everything works in staging
- [ ] Deploy to production
- [ ] Monitor performance and errors

## Important Notes

1. **CRITICAL Migration order**: 
   - **Phase 2**: PHP 8.1 → 8.4 (MUST complete before Symfony 8.0)
   - **Phase 5**: Symfony 5.4 → 8.0 (requires PHP 8.4+)
   - **Phase 6**: PHP 8.4 → 8.5 (MUST be done after Symfony 8.0 is stable)
   - **DO NOT skip phases or change order**
2. **Version dependencies**: Respect PHP/Symfony version requirements strictly
3. **Tests after each rule**: Always run tests after each Rector rule or transformation
4. **Documentation**: Document each step in appropriate tracking files
5. **Rollback**: Plan a rollback strategy in case of major issues
6. **Communication**: Communicate with the team on important changes
7. **Docker**: All commands run via Docker - system PHP/Node.js versions are irrelevant
