# Mikado Graph Template

**Phase**: [Phase Number and Name]
**Goal**: [Main objective to achieve]
**Status**: [ ] Not Started | [ ] In Progress | [ ] Blocked | [ ] Completed
**Started**: [Date]
**Completed**: [Date]
**Last Updated**: [Date]

## Dependency Graph Visualization

```
Goal: [Goal Name]
│
├── Dependency DEP-001: [Name]
│   │
│   ├── Dependency DEP-001-001: [Sub-dependency]
│   │   └── Status: [ ] Pending | [ ] In Progress | [ ] Resolved ✅
│   │
│   └── Dependency DEP-001-002: [Another sub-dependency]
│       └── Status: [ ] Pending | [ ] In Progress | [ ] Resolved ✅
│
├── Dependency DEP-002: [Name]
│   └── Status: [ ] Pending | [ ] In Progress | [ ] Resolved ✅
│
└── [Goal Achieved] ✅
```

## Nodes

### Node DEP-001: [Dependency Name]

- **ID**: DEP-001
- **Description**: [What needs to be done]
- **Status**: [ ] Pending | [ ] In Progress | [ ] Blocked | [ ] Resolved
- **Dependencies**: [List of IDs that must be resolved first, or "None"]
- **Location**: [File(s) or component(s) affected]
- **Reason**: [Why this dependency exists - what blocks the goal]
- **Solution**: [How to resolve this dependency]
- **Test Status**: [ ] Passing | [ ] Failing | [ ] Not Tested
- **Started**: [Date]
- **Resolved Date**: [Date when resolved]
- **Notes**: [Any additional notes]

### Node DEP-002: [Another Dependency]

- **ID**: DEP-002
- **Description**: [What needs to be done]
- **Status**: [ ] Pending | [ ] In Progress | [ ] Blocked | [ ] Resolved
- **Dependencies**: [List of IDs]
- **Location**: [File(s) or component(s)]
- **Reason**: [Why needed]
- **Solution**: [How to resolve]
- **Test Status**: [ ] Passing | [ ] Failing | [ ] Not Tested
- **Started**: [Date]
- **Resolved Date**: [Date]
- **Notes**: [Notes]

## Resolution Order

Based on dependencies, resolve in this order:

1. **[DEP-XXX]**: [Dependency name] - [Why first]
2. **[DEP-YYY]**: [Dependency name] - [Why second]
3. ...
4. **[Goal]**: [Original goal] - [Why last]

## Progress Tracking

### Resolved Dependencies

- [ ] DEP-001: [Name] - Resolved: [Date]
- [ ] DEP-002: [Name] - Resolved: [Date]

### In Progress

- [ ] DEP-003: [Name] - Started: [Date]

### Blocked

- [ ] DEP-004: [Name] - Blocked by: [Reason] - Since: [Date]

### Pending

- [ ] DEP-005: [Name] - Waiting for: [Dependencies]

## Test Results

### After Each Dependency Resolution

| Dependency ID | Tests Before | Tests After | Status |
|---------------|--------------|------------|--------|
| DEP-001 | [Results] | [Results] | [ ] Pass \| [ ] Fail |
| DEP-002 | [Results] | [Results] | [ ] Pass \| [ ] Fail |

### Final Goal Achievement

- [ ] All dependencies resolved
- [ ] All tests passing
- [ ] Goal achieved
- [ ] Graph completed

## Notes

[Any additional notes, observations, or lessons learned]

## Lessons Learned

- [Lesson 1]
- [Lesson 2]
- [Lesson 3]
