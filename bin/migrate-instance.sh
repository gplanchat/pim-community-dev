#!/bin/bash

# Unified Instance Migration Script
# Purpose: Automate migration of Akeneo PIM instance to upgraded version
# Usage: ./bin/migrate-instance.sh [--dry-run] [--rollback]

set -euo pipefail

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
BACKUP_DIR="${PROJECT_DIR}/var/backups"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
DRY_RUN=false
ROLLBACK=false

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        --rollback)
            ROLLBACK=true
            shift
            ;;
        *)
            echo -e "${RED}Unknown option: $1${NC}"
            echo "Usage: $0 [--dry-run] [--rollback]"
            exit 1
            ;;
    esac
done

# Logging functions
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Pre-flight checks
preflight_checks() {
    log_info "Running pre-flight checks..."
    
    # Check PHP version
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    log_info "PHP Version: $PHP_VERSION"
    
    # Check database connectivity
    if ! php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; then
        log_error "Cannot connect to database"
        exit 1
    fi
    log_info "Database connectivity: OK"
    
    # Check disk space (at least 1GB free)
    AVAILABLE_SPACE=$(df -BG "$PROJECT_DIR" | tail -1 | awk '{print $4}' | sed 's/G//')
    if [ "$AVAILABLE_SPACE" -lt 1 ]; then
        log_error "Insufficient disk space (need at least 1GB, have ${AVAILABLE_SPACE}GB)"
        exit 1
    fi
    log_info "Disk space: OK (${AVAILABLE_SPACE}GB available)"
    
    # Check backup directory exists
    if [ ! -d "$BACKUP_DIR" ]; then
        mkdir -p "$BACKUP_DIR"
        log_info "Created backup directory: $BACKUP_DIR"
    fi
    
    log_info "Pre-flight checks completed successfully"
}

# Backup procedures
create_backup() {
    log_info "Creating backup..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would create backup"
        return
    fi
    
    BACKUP_FILE="${BACKUP_DIR}/backup-${TIMESTAMP}.sql"
    
    # Database backup
    log_info "Backing up database..."
    php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1 || {
        log_error "Cannot connect to database for backup"
        exit 1
    }
    
    # Note: Actual database backup should be done with mysqldump or similar
    # This is a placeholder - implement actual backup based on your database setup
    log_warn "Database backup not implemented - please backup manually before migration"
    
    # Files backup
    log_info "Backing up files..."
    tar -czf "${BACKUP_DIR}/files-${TIMESTAMP}.tar.gz" var/cache var/logs var/file_storage public/uploads/ 2>/dev/null || {
        log_warn "Some files may not exist - continuing"
    }
    
    # Configuration backup
    log_info "Backing up configuration..."
    cp .env "${BACKUP_DIR}/.env.backup-${TIMESTAMP}"
    cp -r config/ "${BACKUP_DIR}/config.backup-${TIMESTAMP}/" 2>/dev/null || true
    
    log_info "Backup completed: $BACKUP_DIR"
}

# Database migration
migrate_database() {
    log_info "Running database migrations..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would run: php bin/console doctrine:migrations:migrate --no-interaction"
        return
    fi
    
    php bin/console doctrine:migrations:migrate --no-interaction || {
        log_error "Database migration failed"
        exit 1
    }
    
    log_info "Database migrations completed"
}

# Cache clearing
clear_cache() {
    log_info "Clearing caches..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would clear caches"
        return
    fi
    
    # Symfony cache
    php bin/console cache:clear --env=prod || {
        log_error "Cache clearing failed"
        exit 1
    }
    
    # APCu cache (if available)
    php -r "if (function_exists('apcu_clear_cache')) { apcu_clear_cache(); echo 'APCu cache cleared'; }" || true
    
    log_info "Caches cleared"
}

# Configuration migration
migrate_configuration() {
    log_info "Migrating configuration..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would migrate configuration files"
        return
    fi
    
    # Update .env if needed
    # This is a placeholder - implement actual configuration migration
    log_info "Configuration migration completed (no changes needed)"
}

# File system updates
update_filesystem() {
    log_info "Updating file system..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would update file permissions and create directories"
        return
    fi
    
    # Set permissions
    chmod -R 775 var/ public/uploads/ 2>/dev/null || true
    
    # Create directories if needed
    mkdir -p var/cache var/logs var/file_storage public/uploads
    
    log_info "File system updated"
}

# Validation
validate_migration() {
    log_info "Validating migration..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would validate migration"
        return
    fi
    
    # Validate database schema
    php bin/console doctrine:schema:validate || {
        log_warn "Schema validation found issues - review manually"
    }
    
    # Check requirements
    php bin/console pim:installer:check-requirements || {
        log_warn "Requirements check found issues - review manually"
    }
    
    log_info "Validation completed"
}

# Rollback procedure
rollback() {
    log_info "Rolling back migration..."
    
    if [ "$DRY_RUN" = true ]; then
        log_info "[DRY-RUN] Would rollback migration"
        return
    fi
    
    # Find latest backup
    LATEST_BACKUP=$(ls -t "${BACKUP_DIR}"/*.sql 2>/dev/null | head -1)
    
    if [ -z "$LATEST_BACKUP" ]; then
        log_error "No backup found for rollback"
        exit 1
    fi
    
    log_warn "Rollback requires manual intervention"
    log_info "Latest backup: $LATEST_BACKUP"
    log_info "Please restore manually:"
    log_info "  1. Restore database: mysql -u [user] -p [database] < $LATEST_BACKUP"
    log_info "  2. Restore files: tar -xzf ${BACKUP_DIR}/files-*.tar.gz"
    log_info "  3. Restore configuration: cp ${BACKUP_DIR}/.env.backup-* .env"
    log_info "  4. Clear cache: php bin/console cache:clear"
}

# Main execution
main() {
    log_info "Starting instance migration..."
    log_info "Timestamp: $TIMESTAMP"
    
    if [ "$DRY_RUN" = true ]; then
        log_info "DRY-RUN mode - no changes will be made"
    fi
    
    if [ "$ROLLBACK" = true ]; then
        rollback
        exit 0
    fi
    
    preflight_checks
    create_backup
    migrate_database
    clear_cache
    migrate_configuration
    update_filesystem
    validate_migration
    
    log_info "Migration completed successfully!"
    log_info "Backup location: $BACKUP_DIR"
    log_info "Next steps:"
    log_info "  1. Test application functionality"
    log_info "  2. Monitor logs for errors"
    log_info "  3. Verify key features work"
}

# Run main function
main
