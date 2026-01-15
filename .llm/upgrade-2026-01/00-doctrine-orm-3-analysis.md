# Doctrine ORM 3 Migration Analysis

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Current State

- **Current Doctrine ORM Version**: `^2.9.0` (from `composer.json`)
- **Current Doctrine DBAL Version**: `^2.13.4`
- **Current Doctrine Bundle Version**: `^2.5.5`

## Is Doctrine ORM 3 Required for Symfony 8.0?

### Short Answer: **NO, but RECOMMENDED**

**Symfony 8.0 Compatibility**:
- ✅ **Symfony 8.0 works with Doctrine ORM 2.x** (backward compatible)
- ✅ **Doctrine ORM 2.x is supported until February 2027** (EOL extended)
- ⚠️ **Doctrine ORM 3 is recommended** for long-term support and performance

### Doctrine ORM 2 End of Life

- **Original EOL**: February 2026
- **Extended EOL**: **February 2027** (at least)
- **After EOL**: Only PHP compatibility patches, security fixes, and ORM 3 compatibility improvements

**Source**: https://www.doctrine-project.org/2025/10/08/an-update-on-the-orm-2-end-of-life.html

## Doctrine ORM 3 Benefits

### 1. Performance Improvements
- **Size reduction**: 326 KB (ORM 3) vs 400 KB (ORM 2.18.0)
- **Code coverage**: Increased from 84% to 89%
- **Better performance** in general

### 2. Dependency Simplification
- **Removed dependencies**: `doctrine/cache` and `doctrine/common`
- **PSR-6 cache**: Uses PSR-6 for cache instead of Doctrine-specific cache
- **Simpler dependency tree**

### 3. Breaking Changes (Requires Code Updates)

#### Major Breaking Changes in Doctrine ORM 3:

1. **EntityManager::create() removed**
   - `EntityManager::create()` is deprecated/removed
   - Must use `EntityManagerFactory` instead
   - **Impact**: Found in `DoctrineJobRepository.php` (line 67)

2. **DBAL 4 Required**
   - Doctrine ORM 3 requires DBAL 4
   - DBAL 4 has breaking changes
   - **Current**: DBAL 2.13.4 → **Required**: DBAL 4.x

3. **Deprecated Methods Removed**
   - `EntityRepository::clear()` - removed
   - `ObjectManager::merge()` - removed
   - `Connection::ping()` - removed
   - Various deprecated DBAL methods

4. **Cache Changes**
   - `doctrine/cache` removed
   - Must use PSR-6 cache adapters
   - Configuration changes required

5. **Type System Changes**
   - Some type constants deprecated
   - Type registration changes

## Code Analysis: Potential Issues

### Found in Codebase:

1. **`DoctrineJobRepository.php`** (line 67):
   ```php
   $jobManager = EntityManager::create(
       $jobConn,
       $entityManager->getConfiguration()
   );
   ```
   **Issue**: `EntityManager::create()` is removed in ORM 3
   **Fix**: Use `EntityManagerFactory` instead

2. **Deprecated Methods** (from `phpstan-deprecations.neon`):
   - `executeUpdate()` - deprecated
   - `fetch()` - deprecated
   - `fetchAll()` - deprecated
   - `clear()` - deprecated
   - `merge()` - deprecated
   - `ping()` - deprecated

3. **DBAL Deprecations**:
   - Multiple deprecated DBAL methods in use
   - Need migration to DBAL 4 compatible methods

## Migration Complexity Assessment

### Low Complexity (Can be done during Symfony migration):
- ✅ Update `composer.json` dependencies
- ✅ Resolve deprecated method calls (already tracked in `phpstan-deprecations.neon`)
- ✅ Update cache configuration

### Medium Complexity (Requires code changes):
- ⚠️ Replace `EntityManager::create()` with `EntityManagerFactory`
- ⚠️ Update DBAL method calls (fetchAll → fetchAllAssociative, etc.)
- ⚠️ Remove `merge()` calls (use `persist()` + `flush()` instead)

### High Complexity (Requires careful testing):
- ⚠️ DBAL 4 migration (breaking changes)
- ⚠️ Cache adapter changes
- ⚠️ Type system updates

## Recommendation

### Option 1: Defer Doctrine ORM 3 Migration (RECOMMENDED)

**Timeline**: After Symfony 8.0 migration is stable

**Rationale**:
- ✅ Doctrine ORM 2.x supported until February 2027
- ✅ Symfony 8.0 works perfectly with ORM 2.x
- ✅ Reduces migration complexity
- ✅ Allows focusing on Symfony migration first
- ✅ Can be done as separate phase after main migration

**Plan**:
1. Complete Symfony 8.0 migration with ORM 2.x
2. Stabilize Symfony 8.0 migration
3. Plan Doctrine ORM 3 migration as Phase 9 (separate phase)
4. Target completion before February 2027

### Option 2: Include Doctrine ORM 3 in Symfony Migration

**Timeline**: During Phase 5 (Symfony 8.0 migration)

**Rationale**:
- ✅ One migration instead of two
- ✅ Latest versions together
- ⚠️ Increases complexity significantly
- ⚠️ More breaking changes to handle simultaneously

**Plan**:
1. Update Doctrine ORM to 3.x during Symfony migration
2. Update DBAL to 4.x
3. Fix all breaking changes
4. Test thoroughly

## Decision Matrix

| Factor | Defer (Option 1) | Include (Option 2) |
|--------|------------------|-------------------|
| **Complexity** | Lower | Higher |
| **Risk** | Lower | Higher |
| **Timeline** | Flexible | Fixed |
| **Testing** | Separate | Combined |
| **Rollback** | Easier | Harder |
| **Support** | Until Feb 2027 | Long-term |

## Recommended Approach: **Option 1 - Defer**

### Phase 5: Symfony 8.0 Migration (with Doctrine ORM 2.x)
- Migrate Symfony 5.4 → 8.0
- Keep Doctrine ORM 2.x (compatible)
- Keep Doctrine DBAL 2.x (compatible)
- Focus on Symfony breaking changes only

### Phase 9: Doctrine ORM 3 Migration (after stabilization)
- Separate phase after Symfony 8.0 is stable
- Update to Doctrine ORM 3.x
- Update to Doctrine DBAL 4.x
- Fix breaking changes
- Target: Before February 2027

## Action Items

### For Symfony 8.0 Migration (Phase 5):
- [ ] **Keep Doctrine ORM 2.x** - No need to upgrade
- [ ] **Keep Doctrine DBAL 2.x** - Compatible with Symfony 8.0
- [ ] **Resolve deprecations** - Fix deprecated methods (already tracked)
- [ ] **Test compatibility** - Verify ORM 2.x works with Symfony 8.0

### For Doctrine ORM 3 Migration (Future Phase 9):
- [ ] **Create Phase 9 branch**: `feature/upgrade-2026-01-doctrine-orm-3`
- [ ] **Update dependencies**: `doctrine/orm: ^3.0`, `doctrine/dbal: ^4.0`
- [ ] **Replace EntityManager::create()** - Use EntityManagerFactory
- [ ] **Update DBAL methods** - Migrate to DBAL 4 compatible methods
- [ ] **Update cache configuration** - Migrate to PSR-6
- [ ] **Remove merge() calls** - Replace with persist() + flush()
- [ ] **Test thoroughly** - All tests passing
- [ ] **Target completion**: Before February 2027

## Conclusion

**Doctrine ORM 3 migration is NOT required for Symfony 8.0**, but it's **recommended for long-term support**.

**Best approach**: 
1. ✅ Complete Symfony 8.0 migration with Doctrine ORM 2.x (simpler, lower risk)
2. ✅ Stabilize Symfony 8.0 migration
3. ✅ Plan Doctrine ORM 3 migration as separate phase (Phase 9)
4. ✅ Complete before February 2027 EOL

This approach:
- Reduces complexity during Symfony migration
- Allows focusing on one major migration at a time
- Provides flexibility in timeline
- Maintains support until February 2027
