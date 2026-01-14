# Phase 2 Migration - Next Steps

**Date**: 2026-01-14  
**Branch**: `feature/upgrade-2026-01-php-8.4`  
**Status**: Ready to start Phase 2.1 (PHP_82 rule application)

## Prerequisites

Before starting Phase 2.1, ensure:

1. ✅ Branch is up to date with master (completed)
2. ⚠️ Docker environment is running and ready
3. ⚠️ **Build PHP 8.1 image** (if not already built):
   ```bash
   docker build --target php81-dev -t akeneo/pim-php-dev:8.1 -f Dockerfile.php .
   ```
4. ⚠️ **Start Docker stack**:
   ```bash
   docker compose -f docker-compose.yml -f docker-compose.migration.yml up -d
   ```
5. ⚠️ Composer dependencies are installed: `docker compose run --rm php composer install`
6. ⚠️ Rector is available: `docker compose run --rm php vendor/bin/rector --version`

**Note**: See `.llm/upgrade-2026-01/DOCKER-SETUP.md` for detailed Docker setup instructions.

## Phase 2.1: PHP_82 Rule Application

### Step 1: Dry-run (Review Changes)

```bash
# Execute dry-run to see what will be changed
docker compose run --rm php vendor/bin/rector process --set=PHP_82 --dry-run

# Review the proposed changes carefully
# Note: This will show all files that will be modified
```

**Expected output**: List of files that will be modified by PHP_82 rules.

### Step 2: Review Proposed Changes

- Review each file that will be modified
- Understand what changes PHP_82 rules will make
- Check for potential breaking changes
- Document any concerns

### Step 3: Apply PHP_82 Rule

```bash
# Apply the PHP_82 rule
docker compose run --rm php vendor/bin/rector process --set=PHP_82

# This will modify files according to PHP 8.2 migration rules
```

### Step 4: Run Validations (in order)

**CRITICAL**: Run validations in this exact order:

```bash
# 1. PHPStan - Static analysis (fastest, catches errors early)
docker compose run --rm php vendor/bin/phpstan analyse

# 2. PHPUnit - Unit tests (if PHPStan passes)
docker compose run --rm php vendor/bin/phpunit

# 3. Behat - Functional tests (if PHPUnit passes)
docker compose run --rm php vendor/bin/behat
```

**If PHPStan fails**:
- Fix errors one by one
- Commit each fix separately
- Re-run PHPStan after each fix

**If PHPUnit fails**:
- Fix tests one by one
- Commit each fix separately
- Re-run PHPUnit after each fix

**If Behat fails**:
- Fix tests one by one
- Commit each fix separately
- Re-run Behat after each fix

### Step 5: Commit Changes

**Only commit when ALL validations pass**:

```bash
# Stage modified files
git add src/ tests/ upgrades/ components/

# Commit with Conventional Commits format
git commit -m "feat(php): apply PHP_82 migration rule

Apply Rector PHP_82 rule to migrate codebase to PHP 8.2.
All validations passing: PHPStan → PHPUnit → Behat"
```

### Step 6: Document in Tracking File

Update `.llm/upgrade-2026-01/04-php-tracking.md`:
- Mark application date
- List modified files
- Document test results
- Note any issues encountered and solutions

## Phase 2.2: PHP_83 Rule Application

After Phase 2.1 is complete and committed:

```bash
# Dry-run
docker compose run --rm php vendor/bin/rector process --set=PHP_83 --dry-run

# Apply
docker compose run --rm php vendor/bin/rector process --set=PHP_83

# Validate
docker compose run --rm php vendor/bin/phpstan analyse && \
docker compose run --rm php vendor/bin/phpunit && \
docker compose run --rm php vendor/bin/behat
```

## Phase 2.3: PHP_84 Rule Application

After Phase 2.2 is complete and committed:

```bash
# Dry-run
docker compose run --rm php vendor/bin/rector process --set=PHP_84 --dry-run

# Apply
docker compose run --rm php vendor/bin/rector process --set=PHP_84

# Validate
docker compose run --rm php vendor/bin/phpstan analyse && \
docker compose run --rm php vendor/bin/phpunit && \
docker compose run --rm php vendor/bin/behat
```

## Important Notes

1. **One rule at a time**: Never apply multiple rules in one commit
2. **Always dry-run first**: Review changes before applying
3. **Test after each rule**: All validations must pass before proceeding
4. **Commit atomically**: Each rule application should be a separate commit
5. **Document everything**: Update tracking files after each step
6. **Use Docker**: All commands must be executed via Docker

## Troubleshooting

### If Docker is not available

1. Check Docker installation: `docker --version`
2. Check Docker Compose: `docker compose version` or `docker-compose version`
3. Start Docker services: `docker compose up -d` or `docker-compose up -d`
4. Verify services: `docker compose ps` or `docker-compose ps`

### If Rector is not found

```bash
# Install dependencies
docker compose run --rm php composer install

# Verify Rector
docker compose run --rm php vendor/bin/rector --version
```

### If tests fail

1. Fix one error at a time
2. Commit each fix separately
3. Re-run validations after each fix
4. Document issues and solutions in tracking file
