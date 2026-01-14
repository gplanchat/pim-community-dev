# Pre-Migration Validation and Error Tracking

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Overview

Before starting the migration, we must ensure the codebase is fully functional or track all errors to follow them during migration. Simple errors should be fixed in a preliminary phase.

## Objectives

1. **Verify current codebase state** - Ensure all tests pass
2. **Run PHPStan audit** - Identify static analysis errors
3. **Run all test suites** - PHPUnit, Behat, JS/TS tests
4. **Categorize errors** - Simple fixes vs complex issues
5. **Fix simple errors** - Before migration starts
6. **Track complex errors** - Document for migration phase

## Phase 0: Pre-Migration Validation

### 0.1 Initial State Verification

- [ ] **Checkout master branch**: `git checkout master && git pull origin master`
- [ ] **Verify Docker stack is running**: `docker compose ps`
- [ ] **Install dependencies**: 
  ```bash
  docker compose run --rm php composer install
  docker compose run --rm node yarn install
  ```
- [ ] **Document current state**: Note PHP version, Symfony version, etc.

### 0.2 PHPStan Audit

- [ ] **Run PHPStan analysis**:
  ```bash
  docker compose run --rm php vendor/bin/phpstan analyse > .llm/upgrade-2026-01/phpstan-baseline-report.txt
  ```
- [ ] **Generate detailed report**:
  ```bash
  docker compose run --rm php vendor/bin/phpstan analyse --error-format=table > .llm/upgrade-2026-01/phpstan-baseline-table.txt
  ```
- [ ] **Generate JSON report**:
  ```bash
  docker compose run --rm php vendor/bin/phpstan analyse --error-format=json > .llm/upgrade-2026-01/phpstan-baseline.json
  ```
- [ ] **Count errors by type**:
  ```bash
  docker compose run --rm php vendor/bin/phpstan analyse --error-format=json | jq '.files[] | .messages[] | .message' | sort | uniq -c | sort -rn > .llm/upgrade-2026-01/phpstan-error-summary.txt
  ```
- [ ] **Document PHPStan results** in `00-code-audit-dependencies.md`

### 0.3 PHP Test Suite

- [ ] **Run PHPUnit tests**:
  ```bash
  docker compose run --rm php vendor/bin/phpunit > .llm/upgrade-2026-01/phpunit-baseline-report.txt 2>&1
  ```
- [ ] **Check exit code**: `echo $?` (should be 0 if all pass)
- [ ] **Count test results**:
  ```bash
  docker compose run --rm php vendor/bin/phpunit --testdox | tee .llm/upgrade-2026-01/phpunit-baseline-testdox.txt
  ```
- [ ] **Document test results**: Number of tests, failures, errors, skipped

### 0.4 Behat Functional Tests

- [ ] **Run Behat tests**:
  ```bash
  docker compose run --rm php vendor/bin/behat > .llm/upgrade-2026-01/behat-baseline-report.txt 2>&1
  ```
- [ ] **Check exit code**: `echo $?`
- [ ] **Document Behat results**: Scenarios passed/failed

### 0.5 JavaScript/TypeScript Tests

- [ ] **Run unit tests**:
  ```bash
  docker compose run --rm node yarn unit > .llm/upgrade-2026-01/jest-unit-baseline-report.txt 2>&1
  ```
- [ ] **Run integration tests**:
  ```bash
  docker compose run --rm node yarn integration > .llm/upgrade-2026-01/jest-integration-baseline-report.txt 2>&1
  ```
- [ ] **Run E2E tests** (if applicable):
  ```bash
  docker compose run --rm node yarn test:e2e:run > .llm/upgrade-2026-01/cypress-baseline-report.txt 2>&1
  ```
- [ ] **Document JS/TS test results**

### 0.6 Linting and Code Quality

- [ ] **Run PHP-CS-Fixer check**:
  ```bash
  docker compose run --rm php vendor/bin/php-cs-fixer fix --dry-run --diff > .llm/upgrade-2026-01/php-cs-fixer-baseline-report.txt
  ```
- [ ] **Run ESLint**:
  ```bash
  docker compose run --rm node yarn lint > .llm/upgrade-2026-01/eslint-baseline-report.txt 2>&1
  ```
- [ ] **Run Prettier check**:
  ```bash
  docker compose run --rm node yarn prettier:check > .llm/upgrade-2026-01/prettier-baseline-report.txt 2>&1
  ```
- [ ] **Run TypeScript check**:
  ```bash
  docker compose run --rm node yarn tsc --noEmit > .llm/upgrade-2026-01/typescript-baseline-report.txt 2>&1
  ```

### 0.7 Error Categorization

Categorize all errors found into:

#### Category A: Simple Fixes (Fix Before Migration)
- [ ] **Missing imports** - Add `use` statements
- [ ] **Type hints** - Add missing type declarations
- [ ] **Deprecated function calls** - Simple replacements
- [ ] **Code style** - Formatting issues
- [ ] **Missing null checks** - Add null coalescing operators
- [ ] **Unused variables** - Remove unused code

**Criteria**: Can be fixed in < 30 minutes, no architectural changes needed

#### Category B: Medium Complexity (Fix During Migration)
- [ ] **API changes** - Symfony/Doctrine API updates
- [ ] **Type system improvements** - Complex type hints
- [ ] **Test updates** - Update test expectations
- [ ] **Configuration changes** - Update config files

**Criteria**: Requires understanding migration context, can be fixed alongside version upgrades

#### Category C: Complex Issues (Track and Fix After Migration)
- [ ] **Architectural changes** - Major refactoring needed
- [ ] **Breaking changes** - Require significant code changes
- [ ] **Performance issues** - Need optimization
- [ ] **Security vulnerabilities** - Require careful review

**Criteria**: Require significant effort, better addressed after migration is stable

### 0.8 Error Tracking Document

Create tracking document: `10-error-tracking.md`

- [ ] **List all Category A errors** with fix plan
- [ ] **List all Category B errors** with migration phase assignment
- [ ] **List all Category C errors** with post-migration plan
- [ ] **Create GitHub issues** for Category C errors (if using issue tracker)

### 0.9 Fix Simple Errors (Category A)

- [ ] **Create branch**: `git checkout -b fix/pre-migration-simple-fixes`
- [ ] **Fix errors one by one**:
  - Fix one error
  - Run PHPStan: `docker compose run --rm php vendor/bin/phpstan analyse`
  - Run tests: `docker compose run --rm php vendor/bin/phpunit`
  - Commit atomically: `git commit -m "fix: [description]"`
- [ ] **After all Category A fixes**:
  - Run full test suite
  - Update PHPStan baseline
  - Create PR: `fix/pre-migration-simple-fixes` → `master`
  - Merge after validation

### 0.10 Baseline Documentation

- [ ] **Create baseline snapshot**:
  ```bash
  # Save current state
  echo "PHP Version: $(docker compose run --rm php php -v | head -1)" > .llm/upgrade-2026-01/baseline-state.txt
  echo "Symfony Version: $(docker compose run --rm php composer show symfony/symfony | grep versions)" >> .llm/upgrade-2026-01/baseline-state.txt
  echo "PHPStan Errors: $(docker compose run --rm php vendor/bin/phpstan analyse --error-format=json | jq '.totals.errors')" >> .llm/upgrade-2026-01/baseline-state.txt
  echo "PHPUnit Tests: $(docker compose run --rm php vendor/bin/phpunit --testdox | grep -E 'Tests:|OK|FAILURES')" >> .llm/upgrade-2026-01/baseline-state.txt
  ```
- [ ] **Document baseline metrics** in `10-error-tracking.md`
- [ ] **Commit baseline**: `git commit -m "docs: add pre-migration baseline state"`

## Validation Checklist

Before proceeding to Phase 1 (Migration), verify:

- [ ] ✅ **All Category A errors fixed** and committed
- [ ] ✅ **PHPStan baseline established** and documented
- [ ] ✅ **All tests passing** (or failures documented)
- [ ] ✅ **Error tracking document created** (`10-error-tracking.md`)
- [ ] ✅ **Baseline state documented**
- [ ] ✅ **Simple fixes merged to master**
- [ ] ✅ **Codebase is stable** and ready for migration

## Commands Reference

### Full Validation Run

```bash
# Complete validation sequence
cd /home/gplanchat/PhpstormProjects/kiboko/akeneo/pim-community-dev

# 1. PHPStan
docker compose run --rm php vendor/bin/phpstan analyse --error-format=table > .llm/upgrade-2026-01/phpstan-baseline-table.txt

# 2. PHPUnit
docker compose run --rm php vendor/bin/phpunit --testdox > .llm/upgrade-2026-01/phpunit-baseline-testdox.txt

# 3. Behat
docker compose run --rm php vendor/bin/behat > .llm/upgrade-2026-01/behat-baseline-report.txt 2>&1

# 4. JS/TS Tests
docker compose run --rm node yarn unit > .llm/upgrade-2026-01/jest-unit-baseline-report.txt 2>&1
docker compose run --rm node yarn integration > .llm/upgrade-2026-01/jest-integration-baseline-report.txt 2>&1

# 5. Linting
docker compose run --rm php vendor/bin/php-cs-fixer fix --dry-run --diff > .llm/upgrade-2026-01/php-cs-fixer-baseline-report.txt
docker compose run --rm node yarn lint > .llm/upgrade-2026-01/eslint-baseline-report.txt 2>&1
docker compose run --rm node yarn tsc --noEmit > .llm/upgrade-2026-01/typescript-baseline-report.txt 2>&1
```

### Quick Status Check

```bash
# Quick validation (exit codes only)
docker compose run --rm php vendor/bin/phpstan analyse && echo "✅ PHPStan OK" || echo "❌ PHPStan FAILED"
docker compose run --rm php vendor/bin/phpunit && echo "✅ PHPUnit OK" || echo "❌ PHPUnit FAILED"
docker compose run --rm php vendor/bin/behat && echo "✅ Behat OK" || echo "❌ Behat FAILED"
docker compose run --rm node yarn unit && echo "✅ Jest Unit OK" || echo "❌ Jest Unit FAILED"
```

## Error Tracking Template

See `10-error-tracking.md` for detailed error tracking format.

## Next Steps

After Phase 0 completion:
1. Review `10-error-tracking.md`
2. Ensure all Category A errors are fixed
3. Proceed to Phase 1: Preparation (see `03-action-plan.md`)
