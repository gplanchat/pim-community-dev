# Migration to Latest Versions - 2026-01

This folder contains the complete documentation for migrating the Akeneo PIM Community Dev project to the latest versions of frameworks and languages.

## File Structure

1. **00-executive-summary.md** - Executive summary
2. **00-migration-tools.md** - Migration tools (Rector equivalents for JS/TS)
3. **00-version-verification.md** - Official version verification
4. **00-version-dependencies.md** - **CRITICAL**: Version dependencies matrix (read this first)
5. **00-git-flow-strategy.md** - **CRITICAL**: Git Flow branch strategy (read this first)
6. **00-commit-strategy.md** - **CRITICAL**: Atomic commit strategy with Conventional Commits (read this first)
7. **00-code-audit-dependencies.md** - **CRITICAL**: Code audit, missing dependencies, and cloud service migration plan
8. **00-pre-migration-validation.md** - **CRITICAL**: Pre-migration validation (MUST complete Phase 0 first)
9. **00-doctrine-orm-3-analysis.md** - Doctrine ORM 3 migration analysis (OPTIONAL - can be deferred)
10. **00-dependencies-phase-mapping.md** - **CRITICAL**: Complete dependency phase mapping (which dependencies to update in which phase)
11. **00-instance-migration-guide.md** - **CRITICAL**: Instance migration procedures (automated script and manual procedures)
12. **00-mikado-method-guide.md** - **CRITICAL**: Mikado Method guide for handling complex dependencies
13. **00-github-automation-guide.md** - **CRITICAL**: GitHub automation guide for autonomous PR and Issue management
14. **12-mikado-graph-template.md** - Template for creating Mikado dependency graphs
15. **12-mikado-graph-[phase-name].md** - Mikado graphs for specific phases (created as needed)
16. **11-status-report-template.md** - Status report template with inconsistency detection
17. **11-status-report.md** - Current status report (created/updated by AI assistant during migration)
18. **10-error-tracking.md** - Error tracking and resolution plan
11. **01-technology-audit.md** - Complete technology audit
11. **02-required-migrations.md** - Required migration details
12. **03-action-plan.md** - Detailed action plan with checklist
13. **04-php-tracking.md** - PHP migration tracking (8.1 → 8.4 → 8.5, split in 2 phases)
14. **05-typescript-tracking.md** - TypeScript migration tracking 4.0 → 5.6
15. **06-react-tracking.md** - React migration tracking 17 → 19
16. **07-symfony-tracking.md** - Symfony migration tracking 5.4 → 8.0
17. **08-tools-tracking.md** - Development tools migration tracking
18. **09-final-validation.md** - Final validation and summary
19. **COMMANDS.md** - Quick reference commands
20. **prompt.md** - Session resume prompt
21. **rector-example.php** - Rector configuration example

## Migration Phases Overview

### Phase 0: Pre-Migration Validation ⚠️ MUST COMPLETE FIRST

**Before starting any migration**, complete Phase 0 validation:
1. Run PHPStan audit
2. Run all test suites (PHPUnit, Behat, Jest)
3. Categorize errors (Simple/Medium/Complex)
4. Fix simple errors (Category A)
5. Document baseline state
6. Create error tracking document

**See `00-pre-migration-validation.md` for complete guide.**

**Do NOT proceed until Phase 0 is complete.**

---

## Recommended Migration Order (Phased Approach)

Due to version dependencies between PHP and Symfony, migration MUST follow this exact order:

### Phase 2: PHP 8.1 → 8.4 (MUST complete before Symfony 8.0)
**Git Flow Branch**: `feature/upgrade-2026-01-php-8.4`

1. **PHP 8.1 → 8.2** (Rector rules)
2. **PHP 8.2 → 8.3** (Rector rules)
3. **PHP 8.3 → 8.4** (Rector rules)
4. **Verify PHP 8.4.0+** is working
5. **Merge branch to develop** before starting Phase 5
6. **DO NOT proceed to PHP 8.5 yet** (wait until after Symfony 8.0)

**Parallel migrations** (can be done in same branch or separate branches):
- **TypeScript 4.0 → 5.6** (no PHP/Symfony dependency) - branch: `feature/upgrade-2026-01-typescript-5.6` (optional)
- **React 17 → 19** (no PHP/Symfony dependency) - branch: `feature/upgrade-2026-01-react-19` (optional)

### Phase 5: Symfony 5.4 → 8.0 (requires PHP 8.4+)
**Git Flow Branch**: `feature/upgrade-2026-01-symfony-8.0`
**Prerequisite**: PHP 8.4.0+ MUST be completed AND Phase 2 branch merged to develop

1. **Symfony 5.4 → 6.0** (requires PHP 8.1+)
2. **Symfony 6.0 → 6.4** (requires PHP 8.1+)
3. **Symfony 6.4 → 7.0** (requires PHP 8.2+)
4. **Symfony 7.0 → 8.0** (requires PHP 8.4.0+)
5. **Verify Symfony 8.0** is stable
6. **Merge branch to develop** before starting Phase 6

### Phase 6: PHP 8.4 → 8.5 (after Symfony 8.0)
**Git Flow Branch**: `feature/upgrade-2026-01-php-8.5`
**Prerequisite**: Symfony 8.0 MUST be completed and stable AND Phase 5 branch merged to develop

1. **PHP 8.4 → 8.5** (Rector rules)
2. **Verify Symfony 8.0 compatibility** with PHP 8.5
3. **Final testing**
4. **Merge branch to develop**

### Phase 4: Development Tools (after main migrations)
- **PHPUnit 9 → 10** (requires PHP 8.1+)
- **Jest 26 → 29+** (no PHP dependency)
- **ESLint 6 → 9** (no PHP dependency)

## Methodology

### For PHP/Symfony
- Use **Rector** to apply migration rules
- Apply **one rule at a time**
- Run **all tests** after each rule (in this order):
  - `vendor/bin/phpstan analyse` (static analysis - validates code before runtime tests)
  - `vendor/bin/phpunit` (unit tests)
  - `vendor/bin/behat` (functional tests)
- Document each step in appropriate tracking files

### For React/TypeScript
- Use **react-codemod** for React migrations
- Use **ts-migrate** or manual migration for TypeScript
- Apply **one transformation at a time**
- Run **all tests** after each transformation:
  - `yarn unit`
  - `yarn integration`
  - `yarn test:e2e:run`
- Document each step in appropriate tracking files

## Useful Commands

### PHP
```bash
# Rector (dry-run)
vendor/bin/rector process --set=PHP_83 --dry-run

# Rector (application)
vendor/bin/rector process --set=PHP_83

# PHP Tests
vendor/bin/phpunit
vendor/bin/behat
vendor/bin/phpstan analyse
vendor/bin/php-cs-fixer fix --dry-run
```

### React/TypeScript
```bash
# React Migration
npx react-codemod react-18-upgrade
npx react-codemod react-19-upgrade

# JS/TS Tests
yarn unit
yarn integration
yarn test:e2e:run
yarn lint
yarn tsc --noEmit
```

## Important Notes

1. **CRITICAL: Respect the phased migration order**:
   - **Phase 2**: PHP 8.1 → 8.4 MUST be completed before Symfony 8.0
   - **Phase 5**: Symfony 8.0 requires PHP 8.4.0+ (verify in composer.json)
   - **Phase 6**: PHP 8.4 → 8.5 MUST be done after Symfony 8.0 is stable
   - **DO NOT skip phases or change order**
2. **Git Flow branch strategy**: One branch per phase:
   - Phase 2: `feature/upgrade-2026-01-php-8.4`
   - Phase 5: `feature/upgrade-2026-01-symfony-8.0`
   - Phase 6: `feature/upgrade-2026-01-php-8.5`
3. **Merge to develop**: Each phase branch must be merged to develop before starting next phase
4. **Read `00-version-dependencies.md`** before starting to understand all version requirements
5. **Read `00-git-flow-strategy.md`** before starting to understand branch strategy
6. **Always test after each rule/transformation**
7. **Document each step** in tracking files
8. **Create pull requests** for code review before merging each phase branch
9. **Plan a rollback strategy** in case of major issues
10. **Communicate with the team** on important changes
11. **Use Docker** - system PHP/Node.js versions are irrelevant, check composer.json/package.json only

## Resources

- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [React Migration Guide](https://react.dev/blog/2024/04/25/react-19-upgrade-guide)
- [Rector Documentation](https://getrector.com/documentation)
- [TypeScript Documentation](https://www.typescriptlang.org/docs/)
