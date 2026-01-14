#!/bin/bash
# Script pour mesurer la performance des testsuites PHPUnit

SUITES=(
    "PIM_Migration_Test"
    "Akeneo_Connectivity_Connection_Integration"
    "Akeneo_FileStorage_Integration"
    "Akeneo_Measurement_Integration"
    "Akeneo_Measurement_Acceptance"
    "Akeneo_Measurement_EndToEnd"
    "Akeneo_Connectivity_Connection_EndToEnd"
    "Akeneo_Communication_Channel_Integration"
    "Data_Quality_Insights"
    "Batch_Queue_Acceptance"
    "Enrichment_Product"
    "Category"
    "Identifier_Generator_PhpUnit"
)

echo "Testsuite|Tests|Temps (s)|Temps moyen/test (ms)|Status"
echo "---------|-----|---------|---------------------|------"

for suite in "${SUITES[@]}"; do
    echo "Analyzing $suite..." >&2
    
    START_TIME=$(date +%s)
    
    OUTPUT=$(timeout 300 docker compose run --rm php vendor/bin/phpunit --testsuite="$suite" --testdox 2>&1)
    EXIT_CODE=$?
    END_TIME=$(date +%s)
    
    DURATION=$((END_TIME - START_TIME))
    
    if [ $EXIT_CODE -eq 124 ]; then
        STATUS="TIMEOUT"
        TESTS="N/A"
        AVG_TIME="N/A"
    else
        TESTS=$(echo "$OUTPUT" | grep -E "^(Tests:|OK|FAILURES|ERRORS)" | grep -oE "[0-9]+" | head -1)
        if [ -z "$TESTS" ]; then
            TESTS=$(echo "$OUTPUT" | grep -E "tests?.*assertions?" | grep -oE "[0-9]+" | head -1)
        fi
        
        if [ -z "$TESTS" ] || [ "$TESTS" = "0" ]; then
            TESTS="0"
            STATUS="NO_TESTS"
            AVG_TIME="N/A"
        else
            AVG_TIME=$(echo "scale=2; $DURATION * 1000 / $TESTS" | bc)
            STATUS="OK"
        fi
    fi
    
    echo "$suite|$TESTS|$DURATION|$AVG_TIME|$STATUS"
done
