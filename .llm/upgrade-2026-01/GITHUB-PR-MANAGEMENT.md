# GitHub Pull Request Management

**Date**: 2026-01-14  
**Repository**: https://github.com/gplanchat/pim-community-dev/ (fork repository)  
**Base Repository**: https://github.com/akeneo/pim-community-dev (original - do NOT create PRs here)

## Overview

This document describes how Pull Requests (PRs) should be created and managed during the migration process. **All PRs must be created on the fork repository**, not on the original repository.

## Key Principles

1. **Fork Repository Only**: All PRs must be created on https://github.com/gplanchat/pim-community-dev/
2. **Base Branch**: Always use `master` as the base branch for PRs
3. **AI Assistant Responsibility**: The AI assistant must create and update PRs on GitHub
4. **Regular Updates**: PR descriptions should be updated as migration progresses
5. **No Original Repository**: Do NOT create PRs on akeneo/pim-community-dev

## PR Creation Workflow

### When to Create PR

1. **Early Creation**: Create PR when phase branch is created or when first significant changes are committed
2. **Regular Updates**: Update PR description after each significant step
3. **Final Update**: Update PR with final status before merge

### PR Creation Steps

1. **Push branch to fork repository**:
   ```bash
   git push origin feature/upgrade-2026-01-php-8.4
   ```

2. **Create PR on GitHub**:
   - **Repository**: https://github.com/gplanchat/pim-community-dev/
   - **Base branch**: `master`
   - **Head branch**: Current phase branch (e.g., `feature/upgrade-2026-01-php-8.4`)
   - **Title**: Follow format: `feat(upgrade): Phase X - Description`
   - **Description**: Use template below

3. **PR Title Format**:
   ```
   feat(upgrade): Phase 2 - PHP 8.1 → 8.4 migration
   ```

4. **PR Description Template**:
   ```markdown
   ## 🎯 Objectif

   Cette PR migre PHP de 8.1 vers 8.4 (Phase 2) qui est un prérequis obligatoire avant la migration vers Symfony 8.0.

   **⚠️ IMPORTANT** : Symfony 8.0 nécessite PHP 8.4.0+, cette phase DOIT être complétée avant de commencer la migration Symfony.

   ## 📋 Changements inclus

   ### Phase 2.1: PHP_82 Rule Application
   - [ ] Dockerfile updated for PHP 8.2
   - [ ] Rector PHP_82 rule applied
   - [ ] Tests passing: PHPStan → PHPUnit → Behat

   ### Phase 2.2: PHP_83 Rule Application
   - [ ] Dockerfile updated for PHP 8.3
   - [ ] Rector PHP_83 rule applied
   - [ ] Tests passing

   ### Phase 2.3: PHP_84 Rule Application
   - [ ] Dockerfile updated for PHP 8.4
   - [ ] Rector PHP_84 rule applied
   - [ ] Tests passing
   - [ ] PHP 8.4.0+ verified

   ## 🔄 Prochaines étapes

   Après le merge de cette PR, la Phase 5 (Symfony 5.4 → 8.0) pourra commencer.

   ## 📚 Documentation

   - Plan d'action : `.llm/upgrade-2026-01/03-action-plan.md`
   - Suivi PHP : `.llm/upgrade-2026-01/04-php-tracking.md`
   - Dépendances versions : `.llm/upgrade-2026-01/00-version-dependencies.md`

   ## ✅ Vérifications

   - [x] Branche créée depuis `master`
   - [ ] Tous les tests passent
   - [ ] Documentation mise à jour
   - [ ] Commits atomiques avec format Conventional Commits

   ## 🧪 Tests

   - [ ] PHPStan: `docker compose run --rm php vendor/bin/phpstan analyse`
   - [ ] PHPUnit: `docker compose run --rm php vendor/bin/phpunit`
   - [ ] Behat: `docker compose run --rm php vendor/bin/behat`

   ## 📝 Notes

   - Cette branche suit la stratégie Git Flow avec un commit atomique par changement
   - Toutes les commandes doivent être exécutées via Docker
   - La migration sera effectuée progressivement, une règle Rector à la fois
   ```

## PR Update Workflow

### When to Update PR

1. **After each Rector rule application**: Update description with results
2. **After test execution**: Add test results to PR description
3. **After fixing issues**: Document issues and solutions
4. **Before merge**: Final status update

### PR Update Template

```markdown
## ✅ Phase 2.1 Complete - PHP_82 Rule Applied

### Summary
- ✅ Dockerfile updated for PHP 8.2
- ✅ Rector PHP_82 rule applied
- ✅ All tests passing: PHPStan → PHPUnit → Behat
- ✅ Commits: [list commit SHAs]

### Test Results
- PHPStan: ✅ Passed
- PHPUnit: ✅ Passed (X tests)
- Behat: ✅ Passed (Y scenarios)

### Next Steps
- Phase 2.2: Apply PHP_83 rule
```

## PR Management Commands

### Using GitHub CLI (gh)

```bash
# Create PR
gh pr create --base master --head feature/upgrade-2026-01-php-8.4 \
  --title "feat(upgrade): Phase 2 - PHP 8.1 → 8.4 migration" \
  --body "PR description..."

# Update PR description
gh pr edit <PR_NUMBER> --body "Updated description..."

# View PR
gh pr view <PR_NUMBER>

# List PRs
gh pr list --repo gplanchat/pim-community-dev

# Merge PR
gh pr merge <PR_NUMBER> --merge
```

### Using Git and GitHub API

```bash
# Push branch
git push origin feature/upgrade-2026-01-php-8.4

# Create PR via GitHub API (if needed)
curl -X POST \
  -H "Authorization: token YOUR_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/pulls \
  -d '{
    "title": "feat(upgrade): Phase 2 - PHP 8.1 → 8.4 migration",
    "head": "feature/upgrade-2026-01-php-8.4",
    "base": "master",
    "body": "PR description..."
  }'
```

## PR Lifecycle

1. **Created**: When phase branch is created or first changes committed
2. **In Progress**: Updated regularly as migration progresses
3. **Ready for Review**: When phase is complete and all tests pass
4. **Merged**: After review and approval, merged to `master` on fork repository
5. **Closed**: Branch deleted after merge

## Important Notes

1. **Fork Repository Only**: Never create PRs on akeneo/pim-community-dev
2. **Base Branch**: Always use `master` as base (not `develop`)
3. **AI Assistant**: Must create and update PRs automatically
4. **Regular Updates**: PR description should reflect current progress
5. **Test Results**: Include test results in PR description
6. **Documentation**: Link to tracking files in PR description

## Troubleshooting

### PR Already Exists

If PR already exists for the branch:
```bash
# Update existing PR description
gh pr edit <PR_NUMBER> --body "Updated description..."
```

### Check PR Status

```bash
# View PR details
gh pr view <PR_NUMBER>

# Check PR status
gh pr status
```

### Merge Conflicts

If merge conflicts occur:
```bash
# Update branch from master
git checkout feature/upgrade-2026-01-php-8.4
git pull origin master
# Resolve conflicts
git push origin feature/upgrade-2026-01-php-8.4
```

## PR Checklist

Before creating PR:
- [ ] Branch pushed to fork repository
- [ ] All commits are atomic and follow Conventional Commits
- [ ] Tests are passing (or documented if failing)
- [ ] Documentation updated

Before merging PR:
- [ ] All tests passing
- [ ] Code review completed (if required)
- [ ] PR description updated with final status
- [ ] Tracking files updated
- [ ] Ready for next phase
