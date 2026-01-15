# Migration Base: v7.0.84

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Decision: Use v7.0.84 as Migration Base

**Status**: ✅ **CONFIRMED** - Migration will use `v7.0.84` tag as base instead of `master` branch.

## Rationale

Based on the analysis in `00-branch-analysis-v7-vs-master.md`, `v7.0.84` is the recommended base because:

1. **Stability**: `v7.0.84` is a stable, tested, and validated release tag
2. **Less SaaS code**: Absence of `catalogs` component (714 files in master) and fewer connectivity modifications
3. **Cleaner codebase**: 3,200 fewer commits (upstream) = less complexity
4. **Verified with upstream**: Analysis confirmed with official Akeneo repository
5. **Fewer cloud dependencies**: Less code related to Google Cloud Storage/PubSub
6. **Absent SaaS feature flags**: No feature flags conditioning SaaS behavior
7. **Older PubSub version**: `google/cloud-pubsub ^1.35.0` vs `^1.39.2` in master (fewer recent dependencies)

## Tag Information

- **Tag**: `v7.0.84`
- **Commit**: `2b1e2aec84` (or `5a7a6812728f8413f0e1d9cc696cce66cf870cb9` from upstream)
- **Repository**: Official Akeneo repository (`upstream`)
- **Type**: Release tag (stable version)

## Migration Strategy

### Base Branch Creation

Create the base branch from `v7.0.84`:

```bash
# Fetch upstream tags
git fetch upstream --tags

# Create base branch from v7.0.84
git checkout -b develop v7.0.84

# Or if develop already exists, create a new base branch
git checkout -b base/v7.0.84 v7.0.84
```

### Phase Branches

All phase branches will be created from this base:

- **Phase 0**: `feature/upgrade-2026-01-phase0-validation` (from `v7.0.84`)
- **Phase 2**: `feature/upgrade-2026-01-php-8.4` (from `v7.0.84` or `develop` if created)
- **Phase 5**: `feature/upgrade-2026-01-symfony-8.0` (from Phase 2 merge)
- **Phase 6**: `feature/upgrade-2026-01-php-8.5` (from Phase 5 merge)

### Upstream Tracking

- **Upstream remote**: `upstream` (git@github.com:akeneo/pim-community-dev)
- **Upstream tag**: `upstream/v7.0.84` or `v7.0.84` (local tag)
- **Upstream branch**: `upstream/7.0` (points to v7.0.84)

## Differences from master

### Components Absent in v7.0.84

1. **`components/catalogs/`** - 714 files (present in master, absent in v7.0.84)
2. **Recent connectivity modifications** - Less connectivity code in v7.0.84
3. **SaaS feature flags** - Absent in v7.0.84

### Dependencies Differences

- **PubSub version**: `^1.35.0` in v7.0.84 vs `^1.39.2` in master
- **No `Akeneo\Catalogs\` namespace** in v7.0.84 (present in master)

## Migration Impact

### Advantages

- ✅ Cleaner starting point
- ✅ Less code to migrate
- ✅ Fewer SaaS dependencies
- ✅ More stable base

### Considerations

- ⚠️ Missing recent fixes from master (may need to backport critical fixes)
- ⚠️ Missing catalogs component (if needed, must be added separately)
- ⚠️ Older PubSub version (may need upgrade if security fixes required)

## Action Items

1. ✅ Verify `v7.0.84` tag exists locally or fetch from upstream
2. ✅ Create base branch from `v7.0.84`
3. ✅ Update all documentation to reference `v7.0.84` as base
4. ✅ Update Git Flow strategy to use `v7.0.84` base
5. ✅ Update action plan to reference `v7.0.84` base
6. ⚠️ Analyze critical fixes post-v7.0.84 for potential backporting
7. ⚠️ Verify cloud dependencies compatibility with v7.0.84

## Verification Commands

```bash
# Verify tag exists
git tag | grep "^v7.0.84$"

# Check tag commit
git rev-parse v7.0.84

# View tag information
git show v7.0.84 --no-patch

# Compare with master
git log --oneline v7.0.84..master | wc -l

# Create base branch
git checkout -b develop v7.0.84
```

## References

- **Analysis**: `00-branch-analysis-v7-vs-master.md`
- **Git Flow Strategy**: `00-git-flow-strategy.md`
- **Action Plan**: `03-action-plan.md`
- **Upstream Repository**: git@github.com:akeneo/pim-community-dev
