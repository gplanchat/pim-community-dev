# Code Audit: Missing Dependencies and Cloud Services

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Overview

This document provides a detailed audit of:
1. **PHPStan analysis** of the current codebase state
2. **Missing Composer dependencies** - classes used but not declared in `composer.json`
3. **Cloud service dependencies** - vendor lock-in risks
4. **Open-source alternatives** - migration paths to avoid vendor lock-in

## PHPStan Audit Status

**Status**: ⚠️ **PENDING** - Must be run before migration starts
**Last Run**: [To be completed]
**Errors Found**: [To be completed]
**Warnings Found**: [To be completed]
**Missing Classes Found**: [To be completed]

### PHPStan Configuration

- Configuration file: `phpstan.neon`
- Level: [To be determined]
- Extensions:
  - `phpstan/phpstan-symfony`
  - `phpstan/phpstan-webmozart-assert`

### PHPStan Execution Command

```bash
# Run PHPStan audit (via Docker)
docker compose run --rm php vendor/bin/phpstan analyse

# Generate detailed report with all errors
docker compose run --rm php vendor/bin/phpstan analyse --error-format=table > .llm/upgrade-2026-01/phpstan-report.txt

# Run with memory limit for large codebase
docker compose run --rm php php -d memory_limit=2G vendor/bin/phpstan analyse

# Generate JSON report for analysis
docker compose run --rm php vendor/bin/phpstan analyse --error-format=json > .llm/upgrade-2026-01/phpstan-report.json
```

### PHPStan Analysis Steps

1. **Run initial audit**:
   ```bash
   docker compose run --rm php vendor/bin/phpstan analyse > .llm/upgrade-2026-01/phpstan-initial-report.txt
   ```

2. **Identify missing classes**:
   - Look for errors like "Class X not found"
   - Check if classes are in `composer.json` dependencies
   - Document missing packages

3. **Categorize errors**:
   - Missing dependencies (add to composer.json)
   - Type errors (fix during migration)
   - Deprecation warnings (fix during Symfony migration)

4. **Update this document** with findings

### PHPStan Results Summary

[To be completed after running PHPStan]

## Missing Composer Dependencies

### 1. Google Cloud Firestore ❌ MISSING

**Status**: ⚠️ **CRITICAL** - Used in code but NOT in `composer.json`

**Location**: 
- `src/Akeneo/Platform/Component/Tenant/Infrastructure/FirestoreContextStore.php`

**Classes Used**:
```php
use Google\Cloud\Firestore\DocumentSnapshot;
use Google\Cloud\Firestore\FirestoreClient;
```

**Purpose**: Tenant context storage for multi-tenant architecture

**Current Usage**:
- Stores tenant configuration and context data
- Used by `TenantContextFetcher` to retrieve tenant-specific settings
- Implements `ContextStoreInterface`

**Required Package**: `google/cloud-firestore` ❌ **NOT IN COMPOSER.JSON**

**Installation** (if keeping Firestore):
```bash
composer require google/cloud-firestore
```

**⚠️ RECOMMENDATION**: Do NOT install Firestore. Instead, migrate to MongoDB or PostgreSQL (see migration plan below).

**Vendor Lock-in Risk**: 🔴 **HIGH** - Google Cloud specific service

**Open-Source Alternative**: See migration plan below

---

### 2. Google Cloud PubSub ✅ PRESENT

**Status**: ✅ Present in `composer.json`

**Package**: `google/cloud-pubsub: ^1.39.2`

**Location**: 
- `src/Akeneo/Tool/Bundle/MessengerBundle/Transport/GooglePubSub/`

**Usage**: Message queue transport for Symfony Messenger

**Vendor Lock-in Risk**: 🟡 **MEDIUM** - Google Cloud specific but can be replaced

**Open-Source Alternative**: See migration plan below

---

### 3. AWS S3 ✅ PRESENT

**Status**: ✅ Present in `composer.json`

**Package**: `aws/aws-sdk-php: 3.253.0`

**Location**: 
- Used for file storage via Flysystem adapters
- `src/Akeneo/Tool/Component/FileStorage/`

**Usage**: Object storage for files (catalog, jobs, archives)

**Vendor Lock-in Risk**: 🟡 **MEDIUM** - AWS specific but S3-compatible alternatives exist

**Open-Source Alternative**: See migration plan below

---

### 4. Microsoft Azure Storage ⚠️ PARTIAL

**Status**: ⚠️ **PARTIAL** - Frontend support exists but backend implementation unclear

**Location**: 
- Frontend: `src/Akeneo/Platform/Bundle/ImportExportBundle/front/src/models/Storage.ts`
- Type definition: `MicrosoftAzureStorage`

**Usage**: Import/Export storage configuration (frontend only)

**Backend Implementation**: Not found in PHP codebase

**Vendor Lock-in Risk**: 🟡 **MEDIUM** - Microsoft Azure specific

**Open-Source Alternative**: See migration plan below

---

### 5. Google Cloud Storage ⚠️ MENTIONED

**Status**: ⚠️ Mentioned in code comments but implementation unclear

**Location**: 
- `src/Akeneo/Tool/Component/FileStorage/File/FileStorer.php` (comment mentions GCS)

**Usage**: File storage (similar to S3)

**Vendor Lock-in Risk**: 🟡 **MEDIUM** - Google Cloud specific

**Open-Source Alternative**: See migration plan below

---

## Cloud Service Dependencies Summary

| Service | Status | Package | Lock-in Risk | Priority |
|---------|--------|---------|--------------|----------|
| Google Firestore | ❌ Missing | `google/cloud-firestore` | 🔴 HIGH | **CRITICAL** |
| Google PubSub | ✅ Present | `google/cloud-pubsub` | 🟡 MEDIUM | Medium |
| AWS S3 | ✅ Present | `aws/aws-sdk-php` | 🟡 MEDIUM | Low |
| Azure Storage | ⚠️ Partial | Not found | 🟡 MEDIUM | Low |
| Google Cloud Storage | ⚠️ Mentioned | Not found | 🟡 MEDIUM | Low |

## Migration Plan: Open-Source Alternatives

### 1. Google Firestore → MongoDB / PostgreSQL

**Current Implementation**: `FirestoreContextStore`

**Open-Source Alternatives**:

#### Option A: MongoDB (Recommended)
- **Package**: `mongodb/mongodb`
- **Why**: Document-based NoSQL database similar to Firestore
- **Migration Effort**: Medium
- **Pros**:
  - Similar document model
  - Good PHP support
  - Can be self-hosted or use managed services
  - No vendor lock-in
- **Cons**:
  - Requires MongoDB server setup
  - Different query syntax

**Implementation**:
```php
// New MongoDB implementation
namespace Akeneo\Platform\Component\Tenant\Infrastructure;

use MongoDB\Client;
use MongoDB\Collection;

final readonly class MongoContextStore implements ContextStoreInterface
{
    public function __construct(
        private Client $mongoClient,
        private string $database,
        private string $collection,
    ) {}

    public function findDocumentById(string $documentId): array
    {
        $collection = $this->mongoClient
            ->selectDatabase($this->database)
            ->selectCollection($this->collection);
        
        $document = $collection->findOne(['_id' => $documentId]);
        
        if (!$document) {
            throw new TenantContextNotFoundException(...);
        }
        
        return $document->toArray();
    }
}
```

#### Option B: PostgreSQL JSONB
- **Package**: Doctrine DBAL (already present)
- **Why**: Use existing PostgreSQL database with JSONB columns
- **Migration Effort**: Low
- **Pros**:
  - No additional infrastructure
  - ACID compliance
  - SQL queries on JSON data
- **Cons**:
  - Less flexible than document databases
  - Requires schema changes

**Implementation**:
```php
// PostgreSQL JSONB implementation
namespace Akeneo\Platform\Component\Tenant\Infrastructure;

use Doctrine\DBAL\Connection;

final readonly class PostgresContextStore implements ContextStoreInterface
{
    public function __construct(
        private Connection $connection,
        private string $tableName = 'tenant_context',
    ) {}

    public function findDocumentById(string $documentId): array
    {
        $result = $this->connection->fetchAssociative(
            'SELECT context_data FROM ' . $this->tableName . ' WHERE tenant_id = ?',
            [$documentId]
        );
        
        if (!$result) {
            throw new TenantContextNotFoundException(...);
        }
        
        return json_decode($result['context_data'], true);
    }
}
```

**Recommendation**: **MongoDB** for better Firestore-like experience, or **PostgreSQL JSONB** for simplicity.

---

### 2. Google PubSub → RabbitMQ / Redis Streams

**Current Implementation**: `GooglePubSub` transport for Symfony Messenger

**Open-Source Alternatives**:

#### Option A: RabbitMQ (Recommended)
- **Package**: `symfony/amqp-messenger` (Symfony Messenger transport)
- **Why**: Industry-standard message broker
- **Migration Effort**: Low (Symfony Messenger supports it natively)
- **Pros**:
  - Mature and stable
  - Excellent Symfony integration
  - Rich features (routing, queues, exchanges)
  - No vendor lock-in
- **Cons**:
  - Requires RabbitMQ server
  - Different from PubSub model

**Configuration**:
```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        transports:
            async:
                dsn: 'amqp://guest:guest@localhost:5672/%2f/messages'
```

#### Option B: Redis Streams
- **Package**: `symfony/redis-messenger` (Symfony Messenger transport)
- **Why**: Redis already used for caching
- **Migration Effort**: Low
- **Pros**:
  - No additional infrastructure
  - Fast and lightweight
  - Good for high-throughput scenarios
- **Cons**:
  - Less feature-rich than RabbitMQ
  - Redis persistence considerations

**Configuration**:
```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        transports:
            async:
                dsn: 'redis://localhost:6379/messages'
```

**Recommendation**: **RabbitMQ** for production, **Redis Streams** for simplicity.

---

### 3. AWS S3 → MinIO / Local Filesystem

**Current Implementation**: Flysystem S3 adapter

**Open-Source Alternatives**:

#### Option A: MinIO (Recommended)
- **Package**: `league/flysystem-aws-s3-v3` (S3-compatible)
- **Why**: S3-compatible API, can use MinIO server
- **Migration Effort**: Very Low (just change endpoint)
- **Pros**:
  - S3-compatible API (no code changes needed)
  - Self-hosted
  - Can migrate to real S3 later if needed
  - Already used in Docker setup (`object-storage` service)
- **Cons**:
  - Requires MinIO server maintenance

**Configuration**:
```php
// Already configured in docker-compose.yml
// Just use MinIO endpoint instead of AWS S3
$client = new S3Client([
    'endpoint' => 'http://object-storage:9000',
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key' => 'AKENEO_OBJECT_STORAGE_ACCESS_KEY',
        'secret' => 'AKENEO_OBJECT_STORAGE_SECRET_KEY',
    ],
]);
```

#### Option B: Local Filesystem
- **Package**: `league/flysystem-local` (already available)
- **Why**: Simple file storage
- **Migration Effort**: Very Low
- **Pros**:
  - No additional infrastructure
  - Simple and reliable
- **Cons**:
  - Not suitable for distributed systems
  - No redundancy

**Recommendation**: **MinIO** (already in Docker setup, S3-compatible).

---

### 4. Azure Storage → MinIO / Local Filesystem

**Current Status**: Frontend support only, no backend implementation found

**Recommendation**: 
- If Azure Storage is needed: Use MinIO with Azure-compatible API or implement Azure Flysystem adapter
- If not needed: Remove frontend Azure Storage support

---

### 5. Google Cloud Storage → MinIO / Local Filesystem

**Current Status**: Mentioned in comments, unclear if implemented

**Recommendation**: Same as AWS S3 - use MinIO (S3-compatible).

---

## Migration Priority

### Phase 1: Critical (Before Migration)
1. ✅ **Add missing Firestore dependency** OR migrate to MongoDB/PostgreSQL
2. ✅ **Run PHPStan audit** to identify all missing dependencies
3. ✅ **Document all cloud service usage**

### Phase 2: High Priority (During Migration)
1. **Migrate Firestore → MongoDB/PostgreSQL** (if Firestore is used)
2. **Migrate PubSub → RabbitMQ/Redis** (if PubSub is used in production)
3. **Ensure MinIO is used** instead of AWS S3 (already configured)

### Phase 3: Low Priority (After Migration)
1. Remove unused cloud service code
2. Clean up Azure Storage frontend if not used
3. Update documentation

## Action Items

### Immediate Actions

- [ ] **Run PHPStan audit**: `docker compose run --rm php vendor/bin/phpstan analyse`
- [ ] **Add missing Firestore package**: `composer require google/cloud-firestore` OR implement MongoDB/PostgreSQL alternative
- [ ] **Document PHPStan results** in this file
- [ ] **Identify all `use` statements** that reference missing packages
- [ ] **Create migration tickets** for each cloud service replacement

### Migration Tasks

- [ ] **Firestore Migration**:
  - [ ] Create `MongoContextStore` or `PostgresContextStore` implementation
  - [ ] Update service configuration
  - [ ] Migrate existing data
  - [ ] Remove `FirestoreContextStore`
  - [ ] Remove `google/cloud-firestore` dependency

- [ ] **PubSub Migration** (if used):
  - [ ] Install RabbitMQ or configure Redis Streams
  - [ ] Update Messenger configuration
  - [ ] Test message queue functionality
  - [ ] Remove `google/cloud-pubsub` dependency

- [ ] **S3 Migration**:
  - [ ] Verify MinIO is used (already in Docker)
  - [ ] Update configuration to use MinIO endpoint
  - [ ] Remove AWS credentials dependency
  - [ ] Consider removing `aws/aws-sdk-php` if not needed

## PHPStan Audit Results

[To be completed after running PHPStan]

### Missing Class Errors

[To be completed]

### Type Errors

[To be completed]

### Deprecation Warnings

[To be completed]

## References

- [PHPStan Documentation](https://phpstan.org/)
- [MongoDB PHP Library](https://www.mongodb.com/docs/drivers/php/)
- [RabbitMQ Symfony Messenger](https://symfony.com/doc/current/messenger.html#amqp-transport)
- [MinIO Documentation](https://min.io/docs/)
- [Flysystem Documentation](https://flysystem.thephpleague.com/docs/)
