# Instance Migration Guide

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Overview

This document provides procedures for migrating an existing Akeneo PIM instance to the upgraded version. It includes automated migration scripts and manual procedures when automation is not possible.

## Migration Scenarios

### Scenario 1: Fresh Installation
- New instance with upgraded codebase
- No data migration needed
- Standard installation procedure

### Scenario 2: In-Place Upgrade
- Existing instance upgraded in place
- Data migration required
- Configuration migration required
- Cache clearing required

### Scenario 3: Parallel Migration
- New instance with upgraded codebase
- Data migration from old instance
- Configuration migration
- Validation and switchover

## Pre-Migration Checklist

### Before Starting Migration

- [ ] **Backup database**: Complete database backup
- [ ] **Backup files**: Backup `var/` directory (cache, logs, file storage)
- [ ] **Backup configuration**: Backup `.env`, `config/` directory
- [ ] **Document current state**: Version numbers, configuration, customizations
- [ ] **Verify disk space**: Ensure sufficient space for backup and migration
- [ ] **Verify permissions**: Check file/directory permissions
- [ ] **Test backup restoration**: Verify backup can be restored

### Current State Documentation

Document the following before migration:

```bash
# Document current versions
echo "PHP Version: $(php -v | head -1)" > migration-state-before.txt
echo "Symfony Version: $(composer show symfony/symfony | grep versions)" >> migration-state-before.txt
echo "Doctrine ORM Version: $(composer show doctrine/orm | grep versions)" >> migration-state-before.txt
echo "Database Version: $(mysql --version)" >> migration-state-before.txt
echo "Date: $(date)" >> migration-state-before.txt

# Document configuration
cp .env migration-state-before.env
cp -r config/ migration-state-before-config/
```

## Automated Migration Script

### Unified Migration Script

**Location**: `bin/migrate-instance.sh`

**Purpose**: Automated migration script that handles:
- Database migrations
- Cache clearing
- Configuration updates
- File system updates
- Validation

**Usage**:
```bash
# Dry-run (preview changes)
./bin/migrate-instance.sh --dry-run

# Execute migration
./bin/migrate-instance.sh

# Rollback (if needed)
./bin/migrate-instance.sh --rollback
```

### Script Components

1. **Pre-flight checks**
   - Verify PHP version
   - Verify database connectivity
   - Verify disk space
   - Verify backup exists

2. **Database migration**
   - Run Doctrine migrations
   - Update schema if needed
   - Migrate data if needed

3. **Cache clearing**
   - Clear Symfony cache
   - Clear APCu cache
   - Clear Redis cache (if used)

4. **Configuration migration**
   - Update `.env` files
   - Update `config/` files
   - Migrate service configurations

5. **File system updates**
   - Update file permissions
   - Create required directories
   - Clean temporary files

6. **Validation**
   - Verify database schema
   - Verify configuration
   - Run health checks

## Manual Migration Procedures

### When Automated Script Cannot Be Used

If automated migration is not possible, follow these manual procedures:

### Step 1: Backup

```bash
# Database backup
mysqldump -u [user] -p [database] > backup-$(date +%Y%m%d-%H%M%S).sql

# Files backup
tar -czf backup-files-$(date +%Y%m%d-%H%M%S).tar.gz var/ public/uploads/

# Configuration backup
cp .env .env.backup-$(date +%Y%m%d-%H%M%S)
cp -r config/ config.backup-$(date +%Y%m%d-%H%M%S)/
```

### Step 2: Code Update

```bash
# Pull latest code
git pull origin master

# Update dependencies
composer install --no-dev --optimize-autoloader
yarn install --frozen-lockfile
```

### Step 3: Database Migration

```bash
# Run Doctrine migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Verify migrations
php bin/console doctrine:migrations:status
```

### Step 4: Cache Clearing

```bash
# Clear all caches
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

# Clear APCu cache (if used)
php -r "apcu_clear_cache();"

# Clear Redis cache (if used)
redis-cli FLUSHALL
```

### Step 5: Configuration Update

```bash
# Update .env file
# Review and update environment variables as needed

# Update configuration files
# Review config/packages/*.yaml files
# Update deprecated configurations
```

### Step 6: File System Updates

```bash
# Set permissions
chmod -R 775 var/
chmod -R 775 public/uploads/

# Create directories if needed
mkdir -p var/cache var/logs var/file_storage
```

### Step 7: Validation

```bash
# Verify database schema
php bin/console doctrine:schema:validate

# Run health checks
php bin/console pim:installer:check-requirements

# Test application
# Access application in browser
# Verify key functionality
```

## Rollback Procedure

### If Migration Fails

1. **Stop application** (if running)
2. **Restore database**:
   ```bash
   mysql -u [user] -p [database] < backup-[timestamp].sql
   ```
3. **Restore files**:
   ```bash
   tar -xzf backup-files-[timestamp].tar.gz
   ```
4. **Restore configuration**:
   ```bash
   cp .env.backup-[timestamp] .env
   cp -r config.backup-[timestamp]/* config/
   ```
5. **Restore code** (if needed):
   ```bash
   git checkout [previous-commit]
   composer install
   ```
6. **Clear cache**:
   ```bash
   php bin/console cache:clear
   ```
7. **Verify restoration**: Test application functionality

## Post-Migration Validation

### Verification Checklist

- [ ] **Application starts**: No fatal errors
- [ ] **Database connectivity**: Can connect to database
- [ ] **Authentication works**: Can log in
- [ ] **Key features work**: Product management, import/export, etc.
- [ ] **API works**: REST API endpoints respond correctly
- [ ] **Performance**: Response times acceptable
- [ ] **Logs clean**: No critical errors in logs
- [ ] **Cache working**: Cache is being used correctly

### Health Check Commands

```bash
# Check requirements
php bin/console pim:installer:check-requirements

# Check database schema
php bin/console doctrine:schema:validate

# Check cache
php bin/console cache:pool:list

# Check configuration
php bin/console debug:container
php bin/console debug:config
```

## Troubleshooting

### Common Issues

#### Issue: Database Migration Fails
**Solution**:
1. Check database user permissions
2. Verify database version compatibility
3. Review migration errors in logs
4. Consider manual migration if needed

#### Issue: Cache Not Clearing
**Solution**:
1. Manually delete `var/cache/*`
2. Check file permissions
3. Verify cache adapter configuration

#### Issue: Configuration Errors
**Solution**:
1. Review `config/packages/*.yaml` files
2. Check for deprecated configurations
3. Update to new Symfony 8.0 format

#### Issue: Performance Degradation
**Solution**:
1. Clear all caches
2. Warm up cache
3. Check database indexes
4. Review query performance

## Migration Script Template

See `bin/migrate-instance.sh` for the complete automated migration script template.

## Notes

- **Always backup before migration**
- **Test migration on staging first**
- **Have rollback plan ready**
- **Document any custom procedures**
- **Monitor logs during migration**
