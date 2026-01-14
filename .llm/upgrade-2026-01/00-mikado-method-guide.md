# Mikado Method Guide for Complex Migration Issues

Date: 2026-01-XX
Project: Akeneo PIM Community Dev

## Overview

The **Mikado Method** is a systematic approach to handle complex refactoring and migration tasks that have multiple dependencies. It helps break down complex problems into manageable steps by creating a dependency graph and resolving issues iteratively.

## What is the Mikado Method?

The Mikado Method is a technique for managing complex refactoring tasks where:
- Multiple changes depend on each other
- Some changes cannot be completed until others are done first
- The full scope of dependencies is not immediately clear
- You need to explore and map dependencies before solving them

### Key Principles

1. **Set a Goal**: Define what you want to achieve (the "Mikado" - the top of the dependency tree)
2. **Try to Achieve It**: Attempt to make the change directly
3. **If Blocked**: When you encounter a dependency that prevents the change:
   - Document the dependency
   - Add it to the dependency graph
   - Undo your changes (revert to last working state)
   - Work on the dependency first
4. **Repeat**: Continue until the goal is achieved

## Mikado Method Workflow

### Step 1: Set the Goal (Mikado)

Define the specific migration goal you want to achieve:
- Example: "Migrate Symfony 5.4 → 8.0"
- Example: "Update Doctrine ORM 2.x → 3.x"
- Example: "Fix PHPStan errors for PHP 8.4 compatibility"

### Step 2: Try to Achieve the Goal

Attempt to make the change directly:
- Apply Rector rules
- Update dependencies
- Run tests

### Step 3: Identify Dependencies

When blocked, identify what prevents the change:
- **Dependency Type**: What needs to be fixed first?
- **Dependency Location**: Where is the blocking code?
- **Dependency Reason**: Why does it block the change?

### Step 4: Document and Revert

1. **Document the dependency** in the Mikado graph
2. **Revert changes** to return to last working state
3. **Add dependency to graph** as a new node

### Step 5: Work on Dependencies

1. **Select a dependency** to work on (preferably one with no dependencies)
2. **Set it as new goal** and repeat the process
3. **Resolve the dependency**
4. **Return to original goal**

### Step 6: Repeat Until Goal Achieved

Continue the process until:
- All dependencies are resolved
- Original goal can be achieved
- All tests pass

## Mikado Graph Structure

### Graph Format

```
Goal: [Main Objective]
│
├── Dependency 1: [What blocks the goal]
│   │
│   ├── Dependency 1.1: [What blocks Dependency 1]
│   │   └── [Resolved] ✅
│   │
│   └── Dependency 1.2: [Another blocker]
│       └── [Resolved] ✅
│
├── Dependency 2: [Another blocker]
│   └── [Resolved] ✅
│
└── [Goal Achieved] ✅
```

### Graph Node Properties

Each node in the graph should have:
- **ID**: Unique identifier
- **Description**: What needs to be done
- **Status**: [ ] Pending | [ ] In Progress | [ ] Blocked | [ ] Resolved
- **Dependencies**: List of IDs that must be resolved first
- **Location**: File(s) or component(s) affected
- **Reason**: Why this is needed
- **Solution**: How to resolve it
- **Test Status**: [ ] Passing | [ ] Failing | [ ] Not Tested

## Mikado Method Template

### Template for Tracking Mikado Graph

```markdown
# Mikado Graph: [Goal Name]

**Goal**: [Main objective to achieve]
**Status**: [ ] Not Started | [ ] In Progress | [ ] Blocked | [ ] Completed
**Started**: [Date]
**Completed**: [Date]

## Dependency Graph

### Node 1: [Dependency Name]
- **ID**: DEP-001
- **Description**: [What needs to be done]
- **Status**: [ ] Pending | [ ] In Progress | [ ] Blocked | [ ] Resolved
- **Dependencies**: [List of IDs that must be resolved first, or "None"]
- **Location**: [File(s) or component(s)]
- **Reason**: [Why this is needed]
- **Solution**: [How to resolve it]
- **Test Status**: [ ] Passing | [ ] Failing | [ ] Not Tested
- **Resolved Date**: [Date when resolved]

### Node 2: [Another Dependency]
- **ID**: DEP-002
- ...
```

## Example: Migrating Symfony 5.4 → 8.0

### Goal
Migrate Symfony from 5.4 to 8.0

### Initial Attempt
```bash
# Try to update Symfony
composer update symfony/* --with-all-dependencies
```

### Encountered Dependencies

**Dependency 1**: Doctrine ORM 2.x compatibility
- **Location**: `doctrine/orm: ^2.9.0`
- **Reason**: Symfony 8.0 requires Doctrine ORM 3.x or compatible 2.x
- **Solution**: Keep ORM 2.x (compatible) or migrate to ORM 3.x first

**Dependency 2**: Monolog 2.x → 3.x
- **Location**: `monolog/monolog: ^2.8.0`
- **Reason**: Symfony 8.0 requires Monolog 3.x
- **Solution**: Update Monolog to 3.x first

**Dependency 3**: Twig 3.3 → 4.0
- **Location**: `twig/twig: ^3.3.3`
- **Reason**: Symfony 8.0 may require Twig 4.0
- **Solution**: Update Twig to 4.0 first

### Mikado Graph

```
Goal: Migrate Symfony 5.4 → 8.0
│
├── Dependency 1: Update Monolog 2.x → 3.x
│   │
│   ├── Dependency 1.1: Update symfony/monolog-bundle
│   │   └── [Resolved] ✅
│   │
│   └── Dependency 1.2: Fix breaking changes in logging code
│       └── [Resolved] ✅
│
├── Dependency 2: Update Twig 3.3 → 4.0
│   │
│   ├── Dependency 2.1: Fix Twig template syntax
│   │   └── [Resolved] ✅
│   │
│   └── Dependency 2.2: Update Twig extensions
│       └── [Resolved] ✅
│
├── Dependency 3: Verify Doctrine ORM 2.x compatibility
│   └── [Resolved] ✅ (ORM 2.x is compatible)
│
└── [Goal Achieved] ✅
```

## When to Use Mikado Method

Use the Mikado Method when:

1. **Complex Dependencies**: Multiple changes depend on each other
2. **Unclear Path**: The full scope of dependencies is not immediately clear
3. **Blocking Issues**: Changes are blocked by unresolved dependencies
4. **Large Refactoring**: Major refactoring with many interconnected changes
5. **Risk Management**: Need to minimize risk by working incrementally

### Examples in This Migration

- **Symfony Migration**: Multiple bundles and components need updates
- **Doctrine ORM 3 Migration**: Requires DBAL 4, which has breaking changes
- **PHP Version Migration**: Some dependencies may not support new PHP version
- **React Migration**: Component updates may depend on each other
- **TypeScript Migration**: Type errors may cascade through codebase

## Mikado Method Workflow Integration

### Integration with Migration Phases

1. **Before Starting a Phase**:
   - Identify potential complex issues
   - Set up Mikado graph for the phase
   - Document known dependencies

2. **During Phase Execution**:
   - When blocked, use Mikado Method
   - Document dependencies in graph
   - Resolve dependencies iteratively
   - Update graph as dependencies are resolved

3. **After Phase Completion**:
   - Review Mikado graph
   - Document lessons learned
   - Archive graph for reference

### Integration with Atomic Commits

- **Each resolved dependency** should be committed atomically
- **Each node resolution** should pass all tests
- **Graph updates** should be committed separately
- **Goal achievement** should be committed when all dependencies resolved

## Mikado Graph Tracking File

### File Structure

**Location**: `12-mikado-graph-[phase-name].md`

**Example**: `12-mikado-graph-symfony-8.0.md`

### Content Structure

```markdown
# Mikado Graph: [Phase Name] - [Goal]

**Phase**: [Phase Number and Name]
**Goal**: [Main objective]
**Status**: [ ] Not Started | [ ] In Progress | [ ] Blocked | [ ] Completed
**Started**: [Date]
**Completed**: [Date]

## Dependency Graph

[Visual representation of dependencies]

## Nodes

### Node DEP-001: [Name]
- **Status**: [ ] Pending | [ ] In Progress | [ ] Blocked | [ ] Resolved
- **Dependencies**: [List of IDs]
- **Location**: [Files/components]
- **Reason**: [Why needed]
- **Solution**: [How to resolve]
- **Test Status**: [ ] Passing | [ ] Failing
- **Resolved Date**: [Date]

## Resolution Order

1. [Dependency to resolve first]
2. [Next dependency]
3. ...
4. [Original goal]

## Notes

[Any additional notes or observations]
```

## Best Practices

1. **Start Small**: Begin with the smallest possible goal
2. **Document Everything**: Record all dependencies and blockers
3. **Revert Frequently**: Don't hesitate to undo changes
4. **Test Continuously**: Run tests after each dependency resolution
5. **Commit Atomically**: Commit each resolved dependency separately
6. **Update Graph Regularly**: Keep the graph current
7. **Review Graph**: Periodically review to identify patterns
8. **Share Graph**: Make graph visible to team members

## Tools and Commands

### Git Commits for Mikado Method

```bash
# When starting a Mikado goal
git commit -m "feat(mikado): start Mikado graph for [goal]"

# When documenting a dependency
git commit -m "docs(mikado): add dependency DEP-XXX to graph"

# When resolving a dependency
git commit -m "feat(mikado): resolve dependency DEP-XXX"

# When achieving the goal
git commit -m "feat(mikado): achieve goal [goal name]"
```

### Graph Visualization

Use tools like:
- Mermaid diagrams in Markdown
- PlantUML
- Graphviz
- Simple text-based tree structures

## Troubleshooting

### Common Issues

1. **Circular Dependencies**: 
   - Identify the cycle
   - Break it by introducing an intermediate step
   - Document the solution

2. **Too Many Dependencies**:
   - Break goal into smaller sub-goals
   - Work on sub-goals independently
   - Combine results later

3. **Unclear Dependencies**:
   - Experiment to identify dependencies
   - Document findings
   - Refine graph as you learn

4. **Dependencies Keep Growing**:
   - Review if goal is too large
   - Consider breaking into phases
   - Prioritize critical dependencies

## Example: Complete Mikado Graph

See `12-mikado-graph-example.md` for a complete example of a Mikado graph for a complex migration scenario.

## References

- Mikado Method: https://mikadomethod.wordpress.com/
- Refactoring with Mikado Method: https://www.thoughtworks.com/insights/blog/mikado-method
