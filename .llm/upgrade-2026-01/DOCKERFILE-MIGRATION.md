# Dockerfile Migration Strategy

**Date**: 2026-01-14  
**File**: `Dockerfile.unified`

## Overview

This document describes the strategy for managing Docker images during the migration process. We use a **single unified Dockerfile** (`Dockerfile.unified`) that contains **only the current migration step versions** of PHP and Node.js.

## Key Principles

1. **Single Dockerfile**: One `Dockerfile.unified` file contains both PHP and Node.js stages
2. **Current versions only**: The Dockerfile contains ONLY the versions needed for the current migration step
3. **Sequential updates**: Update PHP and Node versions sequentially as migration progresses
4. **Multi-stage targets**: Use Docker multi-stage builds with specific targets:
   - `php-base`: Base PHP image
   - `php-dev`: PHP with development tools
   - `node`: Node.js image

## Current Migration Step

**Phase 2**: PHP 8.1 → 8.4 Migration
- **Current PHP version**: 8.1
- **Current Node version**: 18
- **Target PHP version**: 8.4 (after Phase 2.3 completion)

## Dockerfile Structure

```dockerfile
# PHP Base Stage
FROM httpd:2.4-bullseye AS php-base
# ... PHP 8.1 installation ...

# PHP Dev Stage  
FROM php-base AS php-dev
# ... Development tools (Xdebug, Blackfire, Composer) ...

# Node Stage
FROM debian:bullseye-slim AS node
# ... Node.js 18 installation ...
```

## Updating Dockerfile for Migration Steps

### Phase 2.1: PHP 8.1 → 8.2

When applying PHP_82 Rector rule, update `Dockerfile.unified`:

1. **Update PHP version references**:
   - Change `php8.1-*` to `php8.2-*` in package names
   - Update paths: `/etc/php/8.1/` → `/etc/php/8.2/`
   - Update symlink: `php-fpm8.1` → `php-fpm8.2`
   - Update PHP repository: Ensure PHP 8.2 repository is configured

2. **Update comments**:
   ```dockerfile
   # Current migration step: Phase 2.1 - PHP 8.1 → 8.2
   # Current PHP version: 8.2
   # Current Node version: 18
   ```

3. **Rebuild images**:
   ```bash
   docker compose build php httpd
   ```

### Phase 2.2: PHP 8.2 → 8.3

When applying PHP_83 Rector rule:

1. **Update PHP version references**:
   - Change `php8.2-*` to `php8.3-*`
   - Update paths: `/etc/php/8.2/` → `/etc/php/8.3/`
   - Update symlink: `php-fpm8.2` → `php-fpm8.3`

2. **Update comments**:
   ```dockerfile
   # Current migration step: Phase 2.2 - PHP 8.2 → 8.3
   # Current PHP version: 8.3
   # Current Node version: 18
   ```

3. **Rebuild images**:
   ```bash
   docker compose build php httpd
   ```

### Phase 2.3: PHP 8.3 → 8.4

When applying PHP_84 Rector rule:

1. **Update PHP version references**:
   - Change `php8.3-*` to `php8.4-*`
   - Update paths: `/etc/php/8.3/` → `/etc/php/8.4/`
   - Update symlink: `php-fpm8.3` → `php-fpm8.4`

2. **Update comments**:
   ```dockerfile
   # Current migration step: Phase 2.3 - PHP 8.3 → 8.4
   # Current PHP version: 8.4
   # Current Node version: 18
   ```

3. **Rebuild images**:
   ```bash
   docker compose build php httpd
   ```

### Phase 3/4: Node.js Migration (if needed)

When migrating Node.js (e.g., Node 18 → 20):

1. **Update Node version**:
   - Change Node.js repository: `node_18.x` → `node_20.x`
   - Update Node.js installation

2. **Update comments**:
   ```dockerfile
   # Current migration step: Phase 3 - Node.js 18 → 20
   # Current PHP version: 8.4
   # Current Node version: 20
   ```

3. **Rebuild images**:
   ```bash
   docker compose build node
   ```

## Docker Compose Configuration

The `docker-compose.yml` file uses `build` instead of `image`:

```yaml
services:
  php:
    build:
      context: .
      dockerfile: Dockerfile.unified
      target: php-dev
  
  httpd:
    build:
      context: .
      dockerfile: Dockerfile.unified
      target: php-dev
  
  node:
    build:
      context: .
      dockerfile: Dockerfile.unified
      target: node
```

## Workflow for Each Migration Step

1. **Before applying Rector rule**:
   - Update `Dockerfile.unified` with new PHP/Node version
   - Update comments to reflect current migration step
   - Commit Dockerfile changes: `git commit -m "chore(docker): update Dockerfile for PHP 8.2"`

2. **Rebuild Docker images**:
   ```bash
   docker compose build php httpd node
   ```

3. **Restart services**:
   ```bash
   docker compose up -d
   ```

4. **Verify versions**:
   ```bash
   docker compose run --rm php php -v
   docker compose run --rm node node -v
   ```

5. **Apply Rector rule**:
   ```bash
   docker compose run --rm php vendor/bin/rector process --set=PHP_82 --dry-run
   ```

## Important Notes

1. **Never keep multiple versions**: The Dockerfile should contain ONLY the current version
2. **Update sequentially**: Update PHP/Node versions one step at a time
3. **Commit Dockerfile changes**: Each Dockerfile update should be committed separately
4. **Rebuild after update**: Always rebuild images after updating Dockerfile
5. **Verify before proceeding**: Check PHP/Node versions before applying Rector rules

## Troubleshooting

### Images not rebuilding

```bash
# Force rebuild without cache
docker compose build --no-cache php httpd node

# Remove old images
docker compose down
docker image prune -f
```

### Version mismatch

```bash
# Check PHP version in container
docker compose run --rm php php -v

# Check Node version in container
docker compose run --rm node node -v

# Verify composer.json PHP requirement matches Dockerfile
grep '"php"' composer.json
```

## Migration Checklist

When updating Dockerfile for a new PHP/Node version:

- [ ] Update PHP/Node version references in Dockerfile.unified
- [ ] Update comments in Dockerfile.unified
- [ ] Update docker-compose.yml if needed (should already use build)
- [ ] Commit Dockerfile changes
- [ ] Rebuild Docker images
- [ ] Verify versions in containers
- [ ] Update tracking files with Dockerfile update date
- [ ] Document in migration tracking file
