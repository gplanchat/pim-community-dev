# Documentation Structure and Organization

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Overview

This document describes the organization structure of all migration documentation. All documents are organized by category and purpose for easy navigation and identification.

## Document Categories

### 00-* : Foundation and Strategy Documents

**Purpose**: Core strategy, methodology, and foundational information that must be read before starting migration.

**Files**:
- `00-executive-summary.md` - Executive summary and overview
- `00-version-dependencies.md` - **CRITICAL**: Version dependencies matrix
- `00-version-verification.md` - Official version verification
- `00-git-flow-strategy.md` - **CRITICAL**: Git Flow branch strategy
- `00-commit-strategy.md` - **CRITICAL**: Atomic commit strategy with Conventional Commits
- `00-migration-tools.md` - Migration tools (Rector equivalents for JS/TS)
- `00-code-audit-dependencies.md` - **CRITICAL**: Code audit, missing dependencies, cloud services
- `00-pre-migration-validation.md` - **CRITICAL**: Pre-migration validation (Phase 0)
- `00-doctrine-orm-3-analysis.md` - Doctrine ORM 3 migration analysis
- `00-dependencies-phase-mapping.md` - **CRITICAL**: Complete dependency phase mapping
- `00-instance-migration-guide.md` - **CRITICAL**: Instance migration procedures
- `00-mikado-method-guide.md` - **CRITICAL**: Mikado Method for complex dependencies
- `00-github-automation-guide.md` - **CRITICAL**: GitHub automation for PR/Issue management
- `00-branch-analysis-v7-vs-master.md` - Analysis of differences between v7.0 and master branches
- `00-plan-completeness-review.md` - Plan completeness review
- `00-documentation-structure.md` - This file (documentation organization)

### 01-* : Audit Documents

**Purpose**: Detailed audits of current state, technologies, and requirements.

**Files**:
- `01-technology-audit.md` - Complete technology audit (PHP, Symfony, React, TypeScript)

### 02-* : Migration Planning Documents

**Purpose**: Detailed migration plans and required migrations.

**Files**:
- `02-required-migrations.md` - Required migration details and steps

### 03-* : Action Plans

**Purpose**: Step-by-step action plans with checklists.

**Files**:
- `03-action-plan.md` - **CRITICAL**: Detailed action plan with checklist

### 04-09 : Tracking Documents

**Purpose**: Progress tracking for each migration phase.

**Files**:
- `04-php-tracking.md` - PHP migration tracking (8.1 → 8.4 → 8.5)
- `05-typescript-tracking.md` - TypeScript migration tracking (4.0 → 5.6)
- `06-react-tracking.md` - React migration tracking (17 → 19)
- `07-symfony-tracking.md` - Symfony migration tracking (5.4 → 8.0)
- `08-tools-tracking.md` - Development tools migration tracking
- `09-final-validation.md` - Final validation and summary

### 10-* : Error and Issue Tracking

**Purpose**: Error tracking, issue resolution, and problem management.

**Files**:
- `10-error-tracking.md` - Error tracking and resolution plan

### 11-* : Status Reports

**Purpose**: Current status reports and templates.

**Files**:
- `11-status-report-template.md` - Status report template with inconsistency detection
- `11-status-report.md` - Current status report (updated by AI assistant)

### 12-* : Mikado Method Documents

**Purpose**: Mikado Method graphs and dependency management.

**Files**:
- `12-mikado-graph-template.md` - Template for creating Mikado dependency graphs
- `12-mikado-graph-[phase-name].md` - Mikado graphs for specific phases (created as needed)

### Other Documents

**Purpose**: Supporting documents, guides, and references.

**Files**:
- `README.md` - General guide and overview
- `prompt.md` - Session resume prompt for AI assistant
- `COMMANDS.md` - Quick reference commands
- `DOCKERFILE-MIGRATION.md` - Dockerfile migration strategy
- `GITHUB-PR-MANAGEMENT.md` - GitHub Pull Request management guide
- `rector-example.php` - Rector configuration example
- `LINKEDIN-POST.md` - LinkedIn post content (marketing)

### Reports and Output Files

**Purpose**: Generated reports, test results, and execution outputs.

**Files** (examples, may vary):
- `*-report.txt` - Test execution reports
- `*-analysis.md` - Analysis reports
- `*-summary.md` - Summary documents
- `*-plan.md` - Planning documents
- `*.sh` - Shell scripts

## Document Naming Convention

### Prefixes

- `00-*` - Foundation and strategy documents (read first)
- `01-*` - Audit documents
- `02-*` - Migration planning documents
- `03-*` - Action plans
- `04-09` - Tracking documents (one per phase)
- `10-*` - Error and issue tracking
- `11-*` - Status reports
- `12-*` - Mikado Method documents

### Suffixes

- `-tracking.md` - Progress tracking document
- `-audit.md` - Audit document
- `-plan.md` - Planning document
- `-guide.md` - Guide document
- `-template.md` - Template document
- `-analysis.md` - Analysis document
- `-report.md` - Report document
- `-summary.md` - Summary document

## Reading Order

### Before Starting Migration

1. `00-executive-summary.md` - Overview
2. `00-version-dependencies.md` - Version requirements
3. `00-git-flow-strategy.md` - Branch strategy
4. `00-commit-strategy.md` - Commit strategy
5. `00-code-audit-dependencies.md` - Dependencies audit
6. `00-pre-migration-validation.md` - Phase 0 validation
7. `00-mikado-method-guide.md` - Complex dependencies
8. `00-github-automation-guide.md` - GitHub automation
9. `01-technology-audit.md` - Technology audit
10. `02-required-migrations.md` - Required migrations
11. `03-action-plan.md` - Action plan

### During Migration

- `04-php-tracking.md` - PHP progress
- `05-typescript-tracking.md` - TypeScript progress
- `06-react-tracking.md` - React progress
- `07-symfony-tracking.md` - Symfony progress
- `08-tools-tracking.md` - Tools progress
- `10-error-tracking.md` - Error tracking
- `11-status-report.md` - Current status
- `12-mikado-graph-*.md` - Dependency graphs (if needed)

### Reference Documents

- `COMMANDS.md` - Quick command reference
- `DOCKERFILE-MIGRATION.md` - Dockerfile updates
- `GITHUB-PR-MANAGEMENT.md` - PR management
- `prompt.md` - Session resume prompt

## Document Status

### Language

- **All documents**: English (standardized)
- **Previous French documents**: Translated to English

### Version Control

- All documents are tracked in Git
- Updates should be committed atomically
- Document changes should follow Conventional Commits

## Maintenance

### Adding New Documents

1. Follow naming convention (prefix + descriptive name)
2. Add to appropriate category section
3. Update this document structure file
4. Update README.md if needed
5. Update prompt.md if critical

### Updating Documents

1. Update document content
2. Update date if significant changes
3. Update tracking files if progress-related
4. Commit atomically with Conventional Commits

## Quick Reference

### Find Documents by Type

```bash
# Foundation documents
ls 00-*.md

# Audit documents
ls 01-*.md

# Planning documents
ls 02-*.md

# Action plans
ls 03-*.md

# Tracking documents
ls 0[4-9]-*.md

# Error tracking
ls 10-*.md

# Status reports
ls 11-*.md

# Mikado graphs
ls 12-*.md
```

### Find Documents by Purpose

```bash
# Strategy documents
grep -l "strategy\|Strategy" *.md

# Audit documents
grep -l "audit\|Audit" *.md

# Tracking documents
grep -l "tracking\|Tracking" *.md

# Guide documents
grep -l "guide\|Guide" *.md
```
