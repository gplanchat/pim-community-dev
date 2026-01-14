# Docker Multi-Stage Setup for Migration

**Date**: 2026-01-14  
**Purpose**: Support multiple PHP and Node.js versions during migration

## Overview

This migration requires building Docker images for different PHP versions (8.1, 8.2, 8.3, 8.4, 8.5) and Node.js versions (18, 20, 22) that are not available as pre-built images.

## Files Created

### 1. `Dockerfile.php`
Multi-stage Dockerfile for PHP versions with targets:
- `php81-dev` - PHP 8.1 development environment
- `php82-dev` - PHP 8.2 development environment
- `php83-dev` - PHP 8.3 development environment
- `php84-dev` - PHP 8.4 development environment
- `php85-dev` - PHP 8.5 development environment
- `default` - Defaults to PHP 8.1 for backward compatibility

### 2. `Dockerfile.node`
Multi-stage Dockerfile for Node.js versions with targets:
- `node18` - Node.js 18 LTS
- `node20` - Node.js 20 LTS
- `node22` - Node.js 22 LTS
- `default` - Defaults to Node.js 18 for backward compatibility

### 3. `docker-compose.migration.yml`
Docker Compose override file that uses build instead of pre-built images.

## Building Images

### Build PHP Images

```bash
# PHP 8.1 (current)
docker build --target php81-dev -t akeneo/pim-php-dev:8.1 -f Dockerfile.php .

# PHP 8.2
docker build --target php82-dev -t akeneo/pim-php-dev:8.2 -f Dockerfile.php .

# PHP 8.3
docker build --target php83-dev -t akeneo/pim-php-dev:8.3 -f Dockerfile.php .

# PHP 8.4 (required for Symfony 8.0)
docker build --target php84-dev -t akeneo/pim-php-dev:8.4 -f Dockerfile.php .

# PHP 8.5 (after Symfony 8.0)
docker build --target php85-dev -t akeneo/pim-php-dev:8.5 -f Dockerfile.php .
```

### Build Node.js Images

```bash
# Node.js 18 (current)
docker build --target node18 -t akeneo/node:18 -f Dockerfile.node .

# Node.js 20
docker build --target node20 -t akeneo/node:20 -f Dockerfile.node .

# Node.js 22
docker build --target node22 -t akeneo/node:22 -f Dockerfile.node .
```

## Usage

### Option 1: Use docker-compose.migration.yml (Recommended)

```bash
# Start with PHP 8.1 (current)
docker compose -f docker-compose.yml -f docker-compose.migration.yml up -d

# For PHP 8.4 migration phase
# Edit docker-compose.migration.yml to use php84-dev target
docker compose -f docker-compose.yml -f docker-compose.migration.yml build php httpd
docker compose -f docker-compose.yml -f docker-compose.migration.yml up -d
```

### Option 2: Build images first, then use docker-compose.yml

```bash
# Build images
docker build --target php84-dev -t akeneo/pim-php-dev:8.4 -f Dockerfile.php .
docker build --target node20 -t akeneo/node:20 -f Dockerfile.node .

# Update docker-compose.yml to use new image tags
# Then start normally
docker compose up -d
```

## Migration Phases

### Phase 2: PHP 8.1 → 8.4 Migration

1. **Start with PHP 8.1** (current):
   ```bash
   docker compose -f docker-compose.yml -f docker-compose.migration.yml up -d
   ```

2. **Build PHP 8.4 image**:
   ```bash
   docker build --target php84-dev -t akeneo/pim-php-dev:8.4 -f Dockerfile.php .
   ```

3. **Update docker-compose.migration.yml** to use `php84-dev` target

4. **Rebuild and restart**:
   ```bash
   docker compose -f docker-compose.yml -f docker-compose.migration.yml build php httpd
   docker compose -f docker-compose.yml -f docker-compose.migration.yml up -d
   ```

### Phase 6: PHP 8.4 → 8.5 Migration

1. **Build PHP 8.5 image**:
   ```bash
   docker build --target php85-dev -t akeneo/pim-php-dev:8.5 -f Dockerfile.php .
   ```

2. **Update docker-compose.migration.yml** to use `php85-dev` target

3. **Rebuild and restart**

## Testing

After building images, verify PHP version:

```bash
# Check PHP version in container
docker compose exec php php -v

# Should show PHP 8.x.x
```

## Troubleshooting

### Build fails with "package not found"

Some PHP versions may not be available in the Sury repository. Check available versions:
```bash
docker run --rm debian:bullseye-slim bash -c "apt-get update && apt-cache search php8"
```

### Image size too large

The multi-stage build should optimize layers. If needed, use `--squash` flag (experimental):
```bash
docker build --squash --target php84-dev -t akeneo/pim-php-dev:8.4 -f Dockerfile.php .
```

### PHP extensions missing

If a PHP extension is missing for a specific version, add it to the Dockerfile.php in the appropriate `base-php${PHP_VERSION}` stage.

## Notes

- The original `Dockerfile` is kept for backward compatibility
- `docker-compose.migration.yml` extends `docker-compose.yml` without modifying it
- Images are tagged with version numbers for easy switching
- All images include development tools (Xdebug, Blackfire, Composer)
