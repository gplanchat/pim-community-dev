# Alternating PHP/Symfony Migration Strategy

Date: 2026-01-15
Project: Akeneo PIM Community Dev

## Overview

**Strategy**: PHP and Symfony migrations are done **alternatively** to minimize risk and validate compatibility at each step.

## Rationale

Instead of migrating all PHP versions first (8.1 → 8.2 → 8.3 → 8.4), then all Symfony versions (5.4 → 6.0 → 6.4 → 7.0 → 8.0), we alternate between PHP and Symfony migrations:

1. **Reduced risk**: Validate compatibility at each step
2. **Easier debugging**: Smaller changes per phase
3. **Better testing**: Test PHP + Symfony combination at each step
4. **Incremental progress**: Can stop at any stable combination

## Migration Order

### Phase 2: PHP 8.1 → 8.2
- **Branch**: `feature/upgrade-2026-01-php-8.2`
- **Base**: `base/v7.0.84`
- **Goal**: Migrate PHP to 8.2
- **Prerequisites**: None

### Phase 3: Symfony 5.4 → 6.0
- **Branch**: `feature/upgrade-2026-01-symfony-6.0`
- **Base**: `base/v7.0.84` (after Phase 2 merge)
- **Goal**: Migrate Symfony to 6.0
- **Prerequisites**: PHP 8.2 (from Phase 2)
- **Requires**: PHP 8.1+ ✅ (we have PHP 8.2)

### Phase 4: PHP 8.2 → 8.3
- **Branch**: `feature/upgrade-2026-01-php-8.3`
- **Base**: `base/v7.0.84` (after Phase 3 merge)
- **Goal**: Migrate PHP to 8.3
- **Prerequisites**: Symfony 6.0 (from Phase 3)

### Phase 5: Symfony 6.0 → 6.4
- **Branch**: `feature/upgrade-2026-01-symfony-6.4`
- **Base**: `base/v7.0.84` (after Phase 4 merge)
- **Goal**: Migrate Symfony to 6.4 (LTS)
- **Prerequisites**: PHP 8.3 (from Phase 4)
- **Requires**: PHP 8.1+ ✅ (we have PHP 8.3)

### Phase 6: PHP 8.3 → 8.4
- **Branch**: `feature/upgrade-2026-01-php-8.4`
- **Base**: `base/v7.0.84` (after Phase 5 merge)
- **Goal**: Migrate PHP to 8.4 (REQUIRED before Symfony 8.0)
- **Prerequisites**: Symfony 6.4 (from Phase 5)
- **⚠️ CRITICAL**: Must complete PHP 8.4 before Symfony 7.0/8.0

### Phase 7: Symfony 6.4 → 7.0
- **Branch**: `feature/upgrade-2026-01-symfony-7.0`
- **Base**: `base/v7.0.84` (after Phase 6 merge)
- **Goal**: Migrate Symfony to 7.0
- **Prerequisites**: PHP 8.4 (from Phase 6)
- **Requires**: PHP 8.2+ ✅ (we have PHP 8.4)

### Phase 8: Symfony 7.0 → 8.0
- **Branch**: `feature/upgrade-2026-01-symfony-8.0`
- **Base**: `base/v7.0.84` (after Phase 7 merge)
- **Goal**: Migrate Symfony to 8.0
- **Prerequisites**: PHP 8.4 (from Phase 6), Symfony 7.0 (from Phase 7)
- **Requires**: PHP 8.4.0+ ✅ (we have PHP 8.4)

### Phase 9: PHP 8.4 → 8.5
- **Branch**: `feature/upgrade-2026-01-php-8.5`
- **Base**: `base/v7.0.84` (after Phase 8 merge)
- **Goal**: Migrate PHP to 8.5
- **Prerequisites**: Symfony 8.0 (from Phase 8) must be stable

## Dependency Validation

Each phase validates compatibility:

| Phase | PHP Version | Symfony Version | Validation |
|-------|-------------|-----------------|------------|
| Start | 8.1 | 5.4 | ✅ |
| Phase 2 | 8.2 | 5.4 | ✅ |
| Phase 3 | 8.2 | 6.0 | ✅ (Symfony 6.0 requires PHP 8.1+) |
| Phase 4 | 8.3 | 6.0 | ✅ |
| Phase 5 | 8.3 | 6.4 | ✅ (Symfony 6.4 requires PHP 8.1+) |
| Phase 6 | 8.4 | 6.4 | ✅ |
| Phase 7 | 8.4 | 7.0 | ✅ (Symfony 7.0 requires PHP 8.2+) |
| Phase 8 | 8.4 | 8.0 | ✅ (Symfony 8.0 requires PHP 8.4.0+) |
| Phase 9 | 8.5 | 8.0 | ✅ |

## Benefits

1. **Smaller changes**: Each phase has fewer changes to review
2. **Better testing**: Test PHP + Symfony combination at each step
3. **Easier rollback**: Can rollback to previous stable combination
4. **Incremental progress**: Can stop at any stable combination (e.g., PHP 8.3 + Symfony 6.4)
5. **Reduced risk**: Validate compatibility incrementally

## Important Notes

- **Never skip phases** - the alternating order is critical
- **Merge to base/v7.0.84** - each phase branch must be merged before starting next phase
- **Test thoroughly** after each phase before merging
- **Create pull requests** for code review before merging each phase branch
- **Use Docker** - system PHP versions are irrelevant

## References

- **Version Dependencies**: `00-version-dependencies.md`
- **Action Plan**: `03-action-plan.md`
- **Git Flow Strategy**: `00-git-flow-strategy.md`
