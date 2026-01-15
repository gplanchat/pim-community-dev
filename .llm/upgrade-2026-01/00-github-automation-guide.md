# GitHub Automation Guide - Autonomous Migration Management

Date: 2026-01-XX
Project: Akeneo PIM Community Dev
**GitHub Repository**: https://github.com/gplanchat/pim-community-dev/ (fork repository)

## Overview

This guide enables the AI assistant to autonomously manage the migration using GitHub Pull Requests and Issues via GitHub MCP (Model Context Protocol). The assistant will create, update, and manage PRs and tickets automatically as the migration progresses.

## GitHub Automation Integration

### Available Methods

The AI assistant should use one of these methods to manage GitHub (in priority order):
1. **GitHub MCP Tools** (if available): Use MCP GitHub server tools - check available MCP tools first
2. **GitHub CLI (`gh`)**: Use `gh` command-line tool (recommended if MCP not available)
3. **GitHub API**: Use REST API via curl or HTTP requests (fallback if CLI not available)
4. **Helper Scripts**: Create scripts to simplify operations (if needed)

### Tool Detection

Before starting, the AI assistant should:
1. **Check for GitHub MCP tools**: Look for available MCP GitHub server tools
2. **Check for GitHub CLI**: Verify `gh` command is available: `which gh` or `gh --version`
3. **Check for GitHub token**: Verify `GITHUB_TOKEN` environment variable is set (for API access)
4. **Choose method**: Use highest priority method available

### GitHub Token Setup

If using GitHub API or CLI, ensure GitHub token is configured:

```bash
# Check if token is set
echo $GITHUB_TOKEN

# For GitHub CLI, authenticate if needed
gh auth login

# Or set token for API usage
export GITHUB_TOKEN="your_token_here"
```

**Token Permissions Required**:
- `repo` - Full control of private repositories
- `issues` - Read and write access to issues
- `pull_requests` - Read and write access to pull requests

### Required Operations

The AI assistant must autonomously:
- **Create Pull Requests** for each migration phase
- **Update PR descriptions** with progress, test results, and status
- **Create GitHub Issues** for tracking complex problems
- **Link Issues to PRs** for traceability
- **Add labels** to organize PRs and Issues
- **Request reviews** when appropriate
- **Merge PRs** when phase is complete and approved
- **Close Issues** when resolved

### GitHub Repository Configuration

- **Fork Repository**: https://github.com/gplanchat/pim-community-dev/
- **Base Repository**: https://github.com/akeneo/pim-community-dev (original - do NOT create PRs here)
- **Base Branch**: `master` (for PRs)
- **Branch Naming**: `feature/upgrade-2026-01-[phase-description]`

## Autonomous Workflow

### Phase Branch Creation and PR Management

#### When Starting a Phase

1. **Create Phase Branch**:
   ```bash
   git checkout master
   git pull origin master
   git checkout -b feature/upgrade-2026-01-[phase-name]
   ```

2. **Create GitHub Issue** for the phase:
   - **Title**: `[Phase X] [Phase Name] Migration`
   - **Description**: Include phase objectives, prerequisites, and checklist
   - **Labels**: `migration`, `phase-[X]`, `[technology]` (e.g., `php`, `symfony`)
   - **Milestone**: `Upgrade 2026-01`
   - **Assign**: Assign to appropriate team member or leave unassigned

3. **Create Pull Request**:
   - **Title**: `feat(upgrade): Phase X - [Phase Name] Migration`
   - **Base**: `master`
   - **Head**: `feature/upgrade-2026-01-[phase-name]`
   - **Description**: 
     - Link to phase issue
     - Phase objectives
     - Prerequisites checklist
     - Progress tracking
     - Test results section
     - Known issues section
   - **Labels**: `migration`, `phase-[X]`, `[technology]`, `work-in-progress`
   - **Draft**: Start as draft PR

4. **Link PR to Issue**: Reference issue in PR description (`Closes #XXX` or `Related to #XXX`)

### During Phase Execution

#### After Each Significant Step

1. **Update PR Description**:
   - Add completed steps to progress section
   - Update test results
   - Document any issues encountered
   - Update status (In Progress, Blocked, Ready for Review)

2. **Create GitHub Issues** for Complex Problems:
   - When encountering complex dependencies (use Mikado Method)
   - When blocked by external factors
   - When manual intervention needed
   - **Title**: `[Phase X] [Problem Description]`
   - **Description**: 
     - Problem description
     - Impact assessment
     - Steps to reproduce (if applicable)
     - Proposed solution
     - Related PR link
   - **Labels**: `migration`, `phase-[X]`, `blocker` (if blocking), `complex`
   - **Link to PR**: Reference PR in issue

3. **Update Mikado Graph** (if using Mikado Method):
   - Document dependencies in `12-mikado-graph-[phase-name].md`
   - Create issues for each major dependency if needed
   - Link issues in Mikado graph

4. **Commit and Push**:
   - Commit atomically after each step
   - Push to phase branch
   - PR will auto-update

### Phase Completion

#### When Phase is Complete

1. **Final PR Update**:
   - Mark all checklist items as complete
   - Add final test results
   - Update status to "Ready for Review"
   - Remove "work-in-progress" label
   - Add "ready-for-review" label

2. **Request Review** (if required):
   - Request review from appropriate team members
   - Add reviewers to PR

3. **After Approval**:
   - Merge PR to `master` branch
   - Close linked issue
   - Update issue with completion summary

4. **Create Next Phase**:
   - Create new phase branch from `master`
   - Create new phase issue
   - Create new phase PR
   - Link to previous phase PR for context

## GitHub Issue Templates

### Phase Issue Template

```markdown
## Phase [X]: [Phase Name]

### Objectives
- [ ] Objective 1
- [ ] Objective 2
- [ ] Objective 3

### Prerequisites
- [ ] Previous phase completed and merged
- [ ] All tests passing on master branch
- [ ] Dependencies installed

### Migration Steps
- [ ] Step 1
- [ ] Step 2
- [ ] Step 3

### Test Results
- PHPStan: [ ] Passing | [ ] Failing
- PHPUnit: [ ] Passing | [ ] Failing
- Behat: [ ] Passing | [ ] Failing
- JS/TS Tests: [ ] Passing | [ ] Failing

### Known Issues
- None

### Related PRs
- PR #XXX: [Phase X-1] Previous Phase

### Status
- [ ] Not Started
- [ ] In Progress
- [ ] Blocked
- [ ] Ready for Review
- [ ] Completed
```

### Complex Problem Issue Template

```markdown
## Problem: [Description]

### Phase
Phase [X]: [Phase Name]

### Impact
- **Severity**: [High/Medium/Low]
- **Blocks**: [What is blocked]
- **Affects**: [What is affected]

### Description
[Detailed problem description]

### Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

### Expected Behavior
[What should happen]

### Actual Behavior
[What actually happens]

### Proposed Solution
[Solution approach]

### Mikado Graph
[Link to Mikado graph node if applicable]

### Related
- PR #XXX: [Related PR]
- Issue #YYY: [Related issue]

### Status
- [ ] Open
- [ ] In Progress
- [ ] Blocked
- [ ] Resolved
```

## GitHub Labels

### Standard Labels

**Migration Labels**:
- `migration` - General migration label
- `phase-0` through `phase-10` - Phase-specific labels
- `upgrade-2026-01` - Migration project label

**Technology Labels**:
- `php` - PHP-related changes
- `symfony` - Symfony-related changes
- `react` - React-related changes
- `typescript` - TypeScript-related changes
- `doctrine` - Doctrine-related changes
- `docker` - Docker-related changes

**Status Labels**:
- `work-in-progress` - Active work
- `ready-for-review` - Ready for code review
- `blocked` - Blocked by dependencies
- `complex` - Complex problem requiring Mikado Method
- `needs-attention` - Requires human intervention

**Priority Labels**:
- `critical` - Critical blocker
- `high` - High priority
- `medium` - Medium priority
- `low` - Low priority

## GitHub MCP/API Commands Reference

**Note**: The AI assistant should use available GitHub MCP tools or GitHub API/CLI to manage PRs and Issues. If GitHub MCP tools are not available, use GitHub CLI (`gh`) or GitHub API directly.

### Available Methods

1. **GitHub MCP Tools** (if available): Use MCP GitHub server tools
2. **GitHub CLI (`gh`)**: Use `gh` command-line tool
3. **GitHub API**: Use REST API via curl or HTTP requests
4. **Python/Node.js Scripts**: Create helper scripts if needed

### Creating Issues

#### Using GitHub CLI (`gh`)

```bash
# Create phase issue
gh issue create \
  --repo gplanchat/pim-community-dev \
  --title "[Phase X] [Phase Name] Migration" \
  --body "[Issue template content]" \
  --label "migration,phase-X,php" \
  --milestone "Upgrade 2026-01"

# Create problem issue
gh issue create \
  --repo gplanchat/pim-community-dev \
  --title "[Phase X] [Problem Description]" \
  --body "[Problem template content]" \
  --label "migration,phase-X,blocker,complex"
```

#### Using GitHub API

```bash
# Create phase issue
curl -X POST \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/issues \
  -d '{
    "title": "[Phase X] [Phase Name] Migration",
    "body": "[Issue template content]",
    "labels": ["migration", "phase-X", "php"],
    "milestone": "Upgrade 2026-01"
  }'
```

### Creating Pull Requests

#### Using GitHub CLI (`gh`)

```bash
# Create phase PR (draft)
gh pr create \
  --repo gplanchat/pim-community-dev \
  --base master \
  --head feature/upgrade-2026-01-[phase-name] \
  --title "feat(upgrade): Phase X - [Phase Name] Migration" \
  --body "[PR description with checklist]" \
  --draft \
  --label "migration,phase-X,work-in-progress"
```

#### Using GitHub API

```bash
# Create phase PR
curl -X POST \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/pulls \
  -d '{
    "title": "feat(upgrade): Phase X - [Phase Name] Migration",
    "body": "[PR description with checklist]",
    "base": "master",
    "head": "feature/upgrade-2026-01-[phase-name]",
    "draft": true
  }'

# Add labels to PR (separate API call)
curl -X POST \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/issues/XXX/labels \
  -d '{"labels": ["migration", "phase-X", "work-in-progress"]}'
```

### Updating Pull Requests

#### Using GitHub CLI (`gh`)

```bash
# Update PR description
gh pr edit XXX \
  --repo gplanchat/pim-community-dev \
  --body "[Updated PR description]"

# Update PR labels
gh pr edit XXX \
  --repo gplanchat/pim-community-dev \
  --add-label "ready-for-review" \
  --remove-label "work-in-progress"

# Change PR from draft to ready
gh pr ready XXX \
  --repo gplanchat/pim-community-dev
```

#### Using GitHub API

```bash
# Update PR description
curl -X PATCH \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/pulls/XXX \
  -d '{
    "body": "[Updated PR description]"
  }'

# Update PR labels
curl -X PUT \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/issues/XXX/labels \
  -d '{"labels": ["migration", "phase-X", "ready-for-review"]}'
```

### Merging Pull Requests

#### Using GitHub CLI (`gh`)

```bash
# Merge PR
gh pr merge XXX \
  --repo gplanchat/pim-community-dev \
  --merge \
  --subject "feat(upgrade): Complete Phase X - [Phase Name] Migration"
```

#### Using GitHub API

```bash
# Merge PR
curl -X PUT \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/pulls/XXX/merge \
  -d '{
    "merge_method": "merge",
    "commit_message": "feat(upgrade): Complete Phase X - [Phase Name] Migration"
  }'
```

### Closing Issues

#### Using GitHub CLI (`gh`)

```bash
# Close issue
gh issue close XXX \
  --repo gplanchat/pim-community-dev \
  --comment "Issue resolved. See PR #YYY for details."
```

#### Using GitHub API

```bash
# Close issue
curl -X PATCH \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/issues/XXX \
  -d '{"state": "closed"}'

# Add comment before closing
curl -X POST \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  https://api.github.com/repos/gplanchat/pim-community-dev/issues/XXX/comments \
  -d '{"body": "Issue resolved. See PR #YYY for details."}'
```

### Helper Scripts

If GitHub MCP tools are not available, create helper scripts:

**`bin/github-create-issue.sh`**:
```bash
#!/bin/bash
# Usage: ./bin/github-create-issue.sh "Title" "Body" "labels" "milestone"
gh issue create \
  --repo gplanchat/pim-community-dev \
  --title "$1" \
  --body "$2" \
  --label "$3" \
  --milestone "$4"
```

**`bin/github-create-pr.sh`**:
```bash
#!/bin/bash
# Usage: ./bin/github-create-pr.sh "branch" "title" "body" "labels"
gh pr create \
  --repo gplanchat/pim-community-dev \
  --base master \
  --head "$1" \
  --title "$2" \
  --body "$3" \
  --draft \
  --label "$4"
```

## Autonomous Workflow Steps

### Step 1: Phase Initialization

1. **Check prerequisites**: Verify previous phase is merged
2. **Create phase branch**: `feature/upgrade-2026-01-[phase-name]`
3. **Create phase issue**: Use phase issue template
4. **Create phase PR**: Use PR template, start as draft
5. **Link PR to issue**: Reference issue in PR
6. **Update status report**: Document in `11-status-report.md`

### Step 2: Phase Execution

For each migration step:

1. **Execute step**: Follow migration methodology
2. **Run tests**: PHPStan → PHPUnit → Behat (for PHP)
3. **If tests pass**:
   - Commit atomically
   - Push to branch
   - Update PR description with progress
4. **If tests fail**:
   - Document failure in PR
   - Create issue if complex problem
   - Use Mikado Method if dependencies detected
   - Fix issues iteratively
   - Update PR as fixes are applied

### Step 3: Complex Problem Handling

When encountering complex dependencies:

1. **Create Mikado graph**: `12-mikado-graph-[phase-name].md`
2. **Document dependencies**: Add to graph
3. **Create issues** for major dependencies:
   - One issue per major dependency block
   - Link issues in Mikado graph
   - Reference issues in PR
4. **Resolve dependencies**: Work through Mikado graph
5. **Update issues**: Mark resolved dependencies as closed
6. **Update PR**: Document resolution in PR description

### Step 4: Phase Completion

1. **Final validation**: Run all tests
2. **Update PR**:
   - Mark all checklist items complete
   - Add final test results
   - Update status to "Ready for Review"
   - Remove "work-in-progress" label
   - Add "ready-for-review" label
3. **Request review** (if required)
4. **After approval**: Merge PR
5. **Close issue**: Update phase issue with completion summary
6. **Update status report**: Mark phase as complete

## Automation Checklist

### For Each Phase

- [ ] **Phase branch created** and pushed
- [ ] **Phase issue created** with template
- [ ] **Phase PR created** as draft
- [ ] **PR linked to issue**
- [ ] **Labels applied** correctly
- [ ] **PR updated** after each significant step
- [ ] **Issues created** for complex problems
- [ ] **Mikado graph created** if complex dependencies
- [ ] **PR merged** when phase complete
- [ ] **Issue closed** with summary
- [ ] **Next phase initialized** from master

### For Each Complex Problem

- [ ] **Issue created** with problem template
- [ ] **Issue linked to PR**
- [ ] **Mikado graph updated** (if applicable)
- [ ] **Issue updated** as problem is resolved
- [ ] **Issue closed** when resolved

## Best Practices

1. **Always create PRs as drafts** initially
2. **Update PR descriptions regularly** (after each significant step)
3. **Create issues for blockers** immediately
4. **Link related items** (PRs, Issues, Mikado graphs)
5. **Use labels consistently** for organization
6. **Document everything** in PR descriptions and issues
7. **Request reviews** when appropriate
8. **Merge only after approval** and all tests passing
9. **Close issues** with resolution summary
10. **Keep PRs focused** on single phase

## Error Handling

### If PR Creation Fails

1. **Check repository access**: Verify MCP has access to fork repository
2. **Check branch exists**: Ensure branch is pushed to remote
3. **Retry with different approach**: May need to create manually first time

### If Issue Creation Fails

1. **Check repository access**: Verify MCP has access
2. **Check label existence**: Ensure labels exist in repository
3. **Create without labels**: Add labels manually if needed

### If Merge Fails

1. **Check merge conflicts**: Resolve conflicts first
2. **Check branch protection**: May need manual merge
3. **Check approval status**: Ensure required approvals are met

## Integration with Existing Workflow

### Integration Points

1. **Phase initialization**: Create issue and PR when phase starts
2. **Step execution**: Update PR after each step
3. **Problem detection**: Create issues for complex problems
4. **Mikado Method**: Link Mikado graph nodes to issues
5. **Phase completion**: Merge PR and close issue
6. **Status reporting**: Reference PRs and issues in `11-status-report.md`

### Workflow Integration

The GitHub automation integrates seamlessly with:
- **Git Flow strategy**: One branch per phase
- **Atomic commits**: Each commit updates PR automatically
- **Mikado Method**: Issues track dependency resolution
- **Status reporting**: PRs and issues provide visibility
- **Error tracking**: Issues document problems and solutions

## Monitoring and Visibility

### PR Dashboard

Monitor all migration PRs:
- Filter by `migration` label
- Filter by phase labels
- Track progress via PR descriptions
- Monitor test results

### Issue Dashboard

Monitor all migration issues:
- Filter by `migration` label
- Filter by phase labels
- Track blockers
- Monitor complex problems

### Status Report Integration

`11-status-report.md` should reference:
- Current phase PR number
- Related issue numbers
- Mikado graph issues
- Blockers and their issues

## Notes

- **All PRs and Issues** must be created on fork repository: https://github.com/gplanchat/pim-community-dev/
- **Do NOT create PRs** on original repository: https://github.com/akeneo/pim-community-dev
- **Use GitHub MCP tools** (if available), **GitHub CLI (`gh`)** (recommended), or **GitHub API** for all GitHub operations
- **Keep PRs and Issues synchronized** with migration progress
- **Document everything** in PR descriptions and issues
- **Store PR/Issue numbers** in `11-status-report.md` for tracking
- **Link related items** (PRs ↔ Issues ↔ Mikado graphs) for traceability
- **Use labels consistently** for organization and filtering
- **Update PRs regularly** (after each significant step) to maintain visibility
- **Close issues with summaries** when resolved to maintain history
