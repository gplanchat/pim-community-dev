# Version Dependencies Matrix

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Critical Version Dependencies

This document outlines all version dependencies that must be respected during the migration.

## PHP and Symfony Dependencies

| Symfony Version | Minimum PHP Required | Maximum PHP Supported | Notes |
|----------------|----------------------|------------------------|-------|
| 5.4 | 7.2.5+ | 8.3.x | Current version |
| 6.0 | 8.1.0+ | 8.5.x | Requires PHP 8.1+ |
| 6.4 | 8.1.0+ | 8.5.x | LTS version, requires PHP 8.1+ |
| 7.0 | 8.2.0+ | 8.5.x | Requires PHP 8.2+ |
| 8.0 | **8.4.0+** | 8.5.x | **Requires PHP 8.4.0+** |

## Migration Phases Based on Dependencies

**⚠️ STRATEGY**: PHP and Symfony migrations are done **alternatively** to minimize risk and validate compatibility at each step.

### Phase 2: PHP 8.1 → 8.2
**Goal**: Migrate PHP to 8.2
**Git Flow Branch**: `feature/upgrade-2026-01-php-8.2`

**Steps**:
1. PHP 8.1 → 8.2 (Rector PHP_82 rule)

**Prerequisites**: None
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (or merged previous phase), merge to `base/v7.0.84` when complete

### Phase 3: Symfony 5.4 → 6.0 (REQUIRES PHP 8.1+)
**Goal**: Migrate Symfony to 6.0
**Git Flow Branch**: `feature/upgrade-2026-01-symfony-6.0`

**Steps**:
1. Symfony 5.4 → 6.0 (requires PHP 8.1+)

**Prerequisites**: 
- PHP 8.2 MUST be completed (Phase 2)
- Phase 2 branch MUST be merged
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (after Phase 2 merge), merge to `base/v7.0.84` when complete

**⚠️ CRITICAL**: Requires PHP 8.1+ (we have PHP 8.2 from Phase 2)

### Phase 4: PHP 8.2 → 8.3
**Goal**: Migrate PHP to 8.3
**Git Flow Branch**: `feature/upgrade-2026-01-php-8.3`

**Steps**:
1. PHP 8.2 → 8.3 (Rector PHP_83 rule)

**Prerequisites**: 
- Symfony 6.0 MUST be completed (Phase 3)
- Phase 3 branch MUST be merged
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (after Phase 3 merge), merge to `base/v7.0.84` when complete

### Phase 5: Symfony 6.0 → 6.4 (REQUIRES PHP 8.1+)
**Goal**: Migrate Symfony to 6.4 (LTS)
**Git Flow Branch**: `feature/upgrade-2026-01-symfony-6.4`

**Steps**:
1. Symfony 6.0 → 6.4 (requires PHP 8.1+)

**Prerequisites**: 
- PHP 8.3 MUST be completed (Phase 4)
- Phase 4 branch MUST be merged
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (after Phase 4 merge), merge to `base/v7.0.84` when complete

**⚠️ CRITICAL**: Requires PHP 8.1+ (we have PHP 8.3 from Phase 4)

### Phase 6: PHP 8.3 → 8.4
**Goal**: Migrate PHP to 8.4 (REQUIRED before Symfony 8.0)
**Git Flow Branch**: `feature/upgrade-2026-01-php-8.4`

**Steps**:
1. PHP 8.3 → 8.4 (Rector PHP_84 rule)

**Prerequisites**: 
- Symfony 6.4 MUST be completed (Phase 5)
- Phase 5 branch MUST be merged
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (after Phase 5 merge), merge to `base/v7.0.84` when complete

**⚠️ CRITICAL**: Must complete PHP 8.4 before starting Symfony 7.0 migration (which requires PHP 8.2+, and Symfony 8.0 requires PHP 8.4.0+)

### Phase 7: Symfony 6.4 → 7.0 (REQUIRES PHP 8.2+)
**Goal**: Migrate Symfony to 7.0
**Git Flow Branch**: `feature/upgrade-2026-01-symfony-7.0`

**Steps**:
1. Symfony 6.4 → 7.0 (requires PHP 8.2+)

**Prerequisites**: 
- PHP 8.4 MUST be completed (Phase 6)
- Phase 6 branch MUST be merged
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (after Phase 6 merge), merge to `base/v7.0.84` when complete

**⚠️ CRITICAL**: Requires PHP 8.2+ (we have PHP 8.4 from Phase 6)

### Phase 8: Symfony 7.0 → 8.0 (REQUIRES PHP 8.4.0+)
**Goal**: Migrate Symfony to 8.0
**Git Flow Branch**: `feature/upgrade-2026-01-symfony-8.0`

**Steps**:
1. Symfony 7.0 → 8.0 (requires PHP 8.4.0+)

**Prerequisites**: 
- PHP 8.4 MUST be completed (Phase 6)
- Symfony 7.0 MUST be completed (Phase 7)
- Phase 7 branch MUST be merged
**Can be done in parallel with**: TypeScript, React migrations
**Branch workflow**: Create from `base/v7.0.84` (after Phase 7 merge), merge to `base/v7.0.84` when complete

**⚠️ CRITICAL**: Requires PHP 8.4.0+ (we have PHP 8.4 from Phase 6)

### Phase 9: PHP 8.4 → 8.5 (AFTER Symfony 8.0)
**Goal**: Migrate PHP to 8.5
**Git Flow Branch**: `feature/upgrade-2026-01-php-8.5`

**Steps**:
1. PHP 8.4 → 8.5 (Rector PHP_85 rule)

**Prerequisites**: 
- Symfony 8.0 MUST be completed and stable (Phase 8)
- Phase 8 branch MUST be merged
**Can be done in parallel with**: Development tools migration
**Branch workflow**: Create from `base/v7.0.84` (after Phase 8 merge), merge to `base/v7.0.84` when complete

**⚠️ CRITICAL**: Must wait until Symfony 8.0 is stable and Phase 8 branch is merged

## Other Dependencies

### PHPUnit
- **PHPUnit 9**: Requires PHP 7.3+
- **PHPUnit 10**: Requires PHP 8.1+
- **Migration**: Can be done after PHP 8.1+ is reached

### React/TypeScript
- **No PHP dependency**: Can be migrated at any time
- **Can be done in parallel** with PHP/Symfony migrations
- **Recommended**: Do during Phase 1 (PHP 8.1 → 8.4) to save time

### Development Tools
- **Jest**: No PHP dependency
- **ESLint**: No PHP dependency
- **Prettier**: No PHP dependency
- **Can be migrated at any time** after main migrations

## Dependency Graph (Alternating Strategy)

```
PHP 8.1 (current)
  ↓
PHP 8.2 ──────┐
  │            │
  │            ↓
  │      Symfony 6.0 (requires PHP 8.1+)
  │            ↓
  │      PHP 8.3 ──────┐
  │            │       │
  │            │       ↓
  │            │  Symfony 6.4 (requires PHP 8.1+)
  │            │       ↓
  │            │  PHP 8.4 ──────┐
  │            │       │        │
  │            │       │        ↓
  │            │       │  Symfony 7.0 (requires PHP 8.2+)
  │            │       │        ↓
  │            │       │  Symfony 8.0 (requires PHP 8.4.0+)
  │            │       │        │
  │            │       │        ↓
  │            └───────┴────────┘
  │                    ↓
  │              PHP 8.5 (requires Symfony 8.0 to be stable)
  │
  └───────────────────────────────────────────────────────────────
```

**Migration Order**:
1. PHP 8.1 → 8.2 (Phase 2)
2. Symfony 5.4 → 6.0 (Phase 3)
3. PHP 8.2 → 8.3 (Phase 4)
4. Symfony 6.0 → 6.4 (Phase 5)
5. PHP 8.3 → 8.4 (Phase 6)
6. Symfony 6.4 → 7.0 (Phase 7)
7. Symfony 7.0 → 8.0 (Phase 8)
8. PHP 8.4 → 8.5 (Phase 9)

## Verification Checklist

Before starting each phase, verify:

### Before Phase 2 (PHP 8.1 → 8.2)
- [ ] Current PHP version in `composer.json`: 8.1.*
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84`
- [ ] Docker stack is running
- [ ] All tests passing
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-php-8.2`

### Before Phase 3 (Symfony 5.4 → 6.0)
- [ ] PHP version in `composer.json`: ^8.2
- [ ] PHP 8.2 migration completed (Phase 2)
- [ ] All PHP 8.2 tests passing
- [ ] Phase 2 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-php-8.2"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] Docker stack configured for PHP 8.2+
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-symfony-6.0`

### Before Phase 4 (PHP 8.2 → 8.3)
- [ ] Symfony 6.0 migration completed (Phase 3)
- [ ] All Symfony 6.0 tests passing
- [ ] Phase 3 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-symfony-6.0"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-php-8.3`

### Before Phase 5 (Symfony 6.0 → 6.4)
- [ ] PHP version in `composer.json`: ^8.3
- [ ] PHP 8.3 migration completed (Phase 4)
- [ ] All PHP 8.3 tests passing
- [ ] Phase 4 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-php-8.3"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-symfony-6.4`

### Before Phase 6 (PHP 8.3 → 8.4)
- [ ] Symfony 6.4 migration completed (Phase 5)
- [ ] All Symfony 6.4 tests passing
- [ ] Phase 5 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-symfony-6.4"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-php-8.4`

### Before Phase 7 (Symfony 6.4 → 7.0)
- [ ] PHP version in `composer.json`: ^8.4
- [ ] PHP 8.4 migration completed (Phase 6)
- [ ] All PHP 8.4 tests passing
- [ ] Phase 6 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-php-8.4"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] Docker stack configured for PHP 8.4+
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-symfony-7.0`

### Before Phase 8 (Symfony 7.0 → 8.0)
- [ ] Symfony 7.0 migration completed (Phase 7)
- [ ] All Symfony 7.0 tests passing
- [ ] Phase 7 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-symfony-7.0"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-symfony-8.0`

### Before Phase 9 (PHP 8.4 → 8.5)
- [ ] Symfony 8.0 migration completed (Phase 8)
- [ ] Symfony 8.0 is stable
- [ ] All Symfony 8.0 tests passing
- [ ] Phase 8 branch merged: `git log base/v7.0.84 | grep "feature/upgrade-2026-01-symfony-8.0"`
- [ ] On `base/v7.0.84` branch: `git checkout base/v7.0.84 && git pull`
- [ ] No critical issues with Symfony 8.0
- [ ] Create branch: `git checkout -b feature/upgrade-2026-01-php-8.5`

## Important Notes

1. **Never skip phases** - the alternating order is critical
2. **Use Git Flow branches** - one branch per phase:
   - Phase 2: `feature/upgrade-2026-01-php-8.2`
   - Phase 3: `feature/upgrade-2026-01-symfony-6.0`
   - Phase 4: `feature/upgrade-2026-01-php-8.3`
   - Phase 5: `feature/upgrade-2026-01-symfony-6.4`
   - Phase 6: `feature/upgrade-2026-01-php-8.4`
   - Phase 7: `feature/upgrade-2026-01-symfony-7.0`
   - Phase 8: `feature/upgrade-2026-01-symfony-8.0`
   - Phase 9: `feature/upgrade-2026-01-php-8.5`
3. **Merge to base/v7.0.84** - each phase branch must be merged to `base/v7.0.84` before starting next phase
4. **Always verify prerequisites** before starting a phase (including previous phase merge)
5. **Use Docker** - system PHP versions are irrelevant
6. **Check composer.json** - this is the source of truth for PHP version
7. **Test thoroughly** after each phase before merging to base/v7.0.84
8. **Create pull requests** for code review before merging each phase branch
9. **Alternating strategy** - PHP and Symfony migrations alternate to minimize risk and validate compatibility at each step