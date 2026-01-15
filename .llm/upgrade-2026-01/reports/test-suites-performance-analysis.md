# PHPUnit Test Suite Performance Analysis

Date: 2026-01-14

## Objective

Evaluate the execution time of each test suite and the average speed of a test within these suites.

**Constraint**: Maximum 5 minutes execution time per test suite for this evaluation.

## Methodology

1. Identify all available test suites in `phpunit.xml`
2. Execute each test suite with time measurement
3. Count the number of tests executed
4. Calculate average speed (time / number of tests)
5. Document results

## Results

| Testsuite | Tests | Time (s) | Avg time/test (ms) | Status |
|-----------|-------|----------|-------------------|--------|
| PIM_Migration_Test | 182 | 253 | 1390.10 | ✅ OK |
| Akeneo_Connectivity_Connection_Integration | N/A | 300 | N/A | ⏱️ TIMEOUT (>5min) |
| Akeneo_FileStorage_Integration | 7 | 7 | 1000.00 | ✅ OK |
| Akeneo_Measurement_Integration | 19 | 8 | 421.05 | ✅ OK |
| Akeneo_Measurement_Acceptance | 70 | 5 | 71.42 | ✅ OK |
| Akeneo_Measurement_EndToEnd | 33 | 30 | 909.09 | ✅ OK |
| Akeneo_Connectivity_Connection_EndToEnd | 129 | 162 | 1255.81 | ✅ OK |
| Akeneo_Communication_Channel_Integration | 11 | 7 | 636.36 | ✅ OK |
| Data_Quality_Insights | 107 | 122 | 1140.18 | ✅ OK |
| Batch_Queue_Acceptance | 1 | 0 | 0.00 | ✅ OK |
| Enrichment_Product | 129 | 132 | 1023.25 | ✅ OK |
| Category | 142 | 178 | 1253.52 | ✅ OK |
| Identifier_Generator_PhpUnit | 113 | 104 | 920.35 | ✅ OK |
| PIM_Integration_Test | 3146 | >300 | N/A | ⏱️ TIMEOUT (>5min) |
| End_to_End | 1182 | >300 | N/A | ⏱️ TIMEOUT (>5min) |
| Akeneo_Connectivity_Connection_Integration | 236 | >300 | N/A | ⏱️ TIMEOUT (>5min) |

## Analysis

### Successful Tests (≤ 5 minutes)
- **Total tests executed**: 943 tests
- **Total time**: ~1,108 seconds (~18.5 minutes)
- **Average time per test**: ~1,175 ms (1.18 seconds)

### Complete Overview
- **Total tests in all suites**: 5507 tests (943 executed + 4564 timeout)
- **Percentage executed successfully**: 17.1% (943/5507)
- **Percentage timeout**: 82.9% (4564/5507)

### Timeout Tests (> 5 minutes)
- **PIM_Integration_Test**: 3146 tests - Timeout after 5 minutes - Largest suite
- **End_to_End**: 1182 tests - Timeout after 5 minutes
- **Akeneo_Connectivity_Connection_Integration**: 236 tests - Timeout after 5 minutes

**Total tests in timeout suites**: 4564 tests

### Statistics by Category

#### Fast Tests (< 100 ms/test)
- Akeneo_Measurement_Acceptance: 71.42 ms/test ⚡

#### Medium Tests (100-1000 ms/test)
- Akeneo_Measurement_Integration: 421.05 ms/test
- Akeneo_Communication_Channel_Integration: 636.36 ms/test
- Akeneo_Measurement_EndToEnd: 909.09 ms/test
- Identifier_Generator_PhpUnit: 920.35 ms/test
- Akeneo_FileStorage_Integration: 1000.00 ms/test
- Enrichment_Product: 1023.25 ms/test

#### Slow Tests (> 1000 ms/test)
- Data_Quality_Insights: 1140.18 ms/test
- Akeneo_Connectivity_Connection_EndToEnd: 1255.81 ms/test
- Category: 1253.52 ms/test
- PIM_Migration_Test: 1390.10 ms/test 🐌

### Behat

| Suite | Scenarios | Time (s) | Avg time/scenario (ms) | Status |
|-------|-----------|----------|------------------------|--------|
| Behat_critical | 100 | ~5 | ~50 | ✅ DRY_RUN |

## Recommendations

### For Test Suites That Timeout
1. **PIM_Integration_Test** (3146 tests): 
   - Requires parallel execution or much longer timeout
   - Estimate: ~62 minutes (3146 tests × 1.18s/test)
   - Consider execution by subdirectories
   
2. **End_to_End** (1182 tests):
   - Requires longer timeout
   - Estimate: ~23 minutes (1182 tests × 1.18s/test)
   - Critical tests to execute separately
   
3. **Akeneo_Connectivity_Connection_Integration** (236 tests):
   - Requires longer timeout
   - Estimate: ~5 minutes (236 tests × 1.18s/test) - Close to limit
   - Analyze slowness causes (maybe fixture issues)

### Possible Optimizations
- Parallel execution of independent test suites
- Fixture cache to reduce setup time
- Selective execution of critical tests only

## Notes

- Test suites exceeding 5 minutes will be stopped and marked as "TIMEOUT"
- Errors will be documented but will not block performance analysis
- **PIM_Integration_Test** is the largest suite and requires adapted execution strategy
- Average times are calculated only for successfully completed suites
- Behat dry-run is very fast (~50ms/scenario) but real execution will be much longer
