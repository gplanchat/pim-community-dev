# Migration Restart: Base v7.0.84

Date: 2026-01-XX
Status: ✅ **READY TO START**

## Decision

**Migration base changed from `master` to `v7.0.84` tag.**

This decision is based on the analysis in `00-branch-analysis-v7-vs-master.md` which recommends using `v7.0.84` as the migration base.

## Base Branch Created

✅ **Base branch created**: `base/v7.0.84` (from tag `v7.0.84`)

**Tag information**:
- **Tag**: `v7.0.84`
- **Commit**: `2b1e2aec84`
- **Message**: "Preparing version 7.0.84 (#20579)"

## Next Steps

### 1. Verify Base Branch

```bash
# Checkout base branch
git checkout base/v7.0.84

# Verify tag
git log --oneline -1

# Verify tests pass
docker compose run --rm php vendor/bin/phpstan analyse
docker compose run --rm php vendor/bin/phpunit
docker compose run --rm php vendor/bin/behat
```

### 2. Create Phase 0 Branch

```bash
# From base branch
git checkout base/v7.0.84
git pull origin base/v7.0.84  # If pushed remotely

# Create Phase 0 branch
git checkout -b feature/upgrade-2026-01-phase0-validation

# Work on Phase 0 validation
# ... follow Phase 0 steps from 00-pre-migration-validation.md
```

### 3. Create Phase 2 Branch

```bash
# After Phase 0 is complete and merged
git checkout base/v7.0.84
git pull origin base/v7.0.84

# Create Phase 2 branch
git checkout -b feature/upgrade-2026-01-php-8.4

# Work on PHP 8.1 → 8.4 migration
# ... follow Phase 2 steps from 03-action-plan.md
```

## Updated Documentation

The following documents have been updated to reference `v7.0.84` as base:

- ✅ `00-migration-base-v7.0.84.md` - New document explaining base choice
- ✅ `00-git-flow-strategy.md` - Updated to use `base/v7.0.84`
- ✅ `03-action-plan.md` - Updated all references from `master` to `base/v7.0.84`
- ✅ `00-branch-analysis-v7-vs-master.md` - Already recommends v7.0.84

## Differences from Previous Migration

### Previous Migration (from master)
- Base: `master` branch
- Includes: 3,200+ commits from master
- Includes: `catalogs` component (714 files)
- Includes: Recent SaaS features

### New Migration (from v7.0.84)
- Base: `v7.0.84` tag
- Excludes: 3,200+ commits from master
- Excludes: `catalogs` component
- Excludes: Recent SaaS features
- **Cleaner starting point**

## Important Notes

1. **All phase branches** must be created from `base/v7.0.84` (or merged branches)
2. **Pull Requests** should target `base/v7.0.84` (or `master` if base branch doesn't exist remotely)
3. **Previous work** on `feature/upgrade-2026-01-php-8.4` from master is **not compatible** with v7.0.84 base
4. **Start fresh** from v7.0.84 for clean migration

## Verification Checklist

- [x] Tag `v7.0.84` exists locally
- [x] Base branch `base/v7.0.84` created
- [x] Documentation updated
- [ ] Base branch pushed to remote (if needed)
- [ ] Tests verified on base branch
- [ ] Phase 0 branch created
- [ ] Phase 0 validation started

## References

- **Base Analysis**: `00-branch-analysis-v7-vs-master.md`
- **Base Documentation**: `00-migration-base-v7.0.84.md`
- **Git Flow**: `00-git-flow-strategy.md`
- **Action Plan**: `03-action-plan.md`
- **Pre-Migration**: `00-pre-migration-validation.md`
