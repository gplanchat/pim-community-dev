# Analyse de performance des testsuites PHPUnit

Date: 2026-01-14

## Objectif

Évaluer le temps d'exécution de chaque testsuite et la vitesse moyenne d'un test au sein de ces suites.

**Contrainte**: Maximum 5 minutes d'exécution par testsuite pour cette évaluation.

## Méthodologie

1. Identifier toutes les testsuites disponibles dans `phpunit.xml`
2. Exécuter chaque testsuite avec mesure du temps
3. Compter le nombre de tests exécutés
4. Calculer la vitesse moyenne (temps / nombre de tests)
5. Documenter les résultats

## Résultats

| Testsuite | Tests | Temps (s) | Temps moyen/test (ms) | Status |
|-----------|-------|-----------|----------------------|--------|
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

## Analyse

### Tests réussis (≤ 5 minutes)
- **Total tests exécutés**: 943 tests
- **Temps total**: ~1,108 secondes (~18.5 minutes)
- **Temps moyen par test**: ~1,175 ms (1.18 secondes)

### Vue d'ensemble complète
- **Total tests dans toutes les suites**: 5507 tests (943 exécutés + 4564 timeout)
- **Pourcentage exécuté avec succès**: 17.1% (943/5507)
- **Pourcentage timeout**: 82.9% (4564/5507)

### Tests timeout (> 5 minutes)
- **PIM_Integration_Test**: 3146 tests - Timeout après 5 minutes - La plus grande suite
- **End_to_End**: 1182 tests - Timeout après 5 minutes
- **Akeneo_Connectivity_Connection_Integration**: 236 tests - Timeout après 5 minutes

**Total tests dans les suites timeout**: 4564 tests

### Statistiques par catégorie

#### Tests rapides (< 100 ms/test)
- Akeneo_Measurement_Acceptance: 71.42 ms/test ⚡

#### Tests moyens (100-1000 ms/test)
- Akeneo_Measurement_Integration: 421.05 ms/test
- Akeneo_Communication_Channel_Integration: 636.36 ms/test
- Akeneo_Measurement_EndToEnd: 909.09 ms/test
- Identifier_Generator_PhpUnit: 920.35 ms/test
- Akeneo_FileStorage_Integration: 1000.00 ms/test
- Enrichment_Product: 1023.25 ms/test

#### Tests lents (> 1000 ms/test)
- Data_Quality_Insights: 1140.18 ms/test
- Akeneo_Connectivity_Connection_EndToEnd: 1255.81 ms/test
- Category: 1253.52 ms/test
- PIM_Migration_Test: 1390.10 ms/test 🐌

### Behat

| Suite | Scenarios | Temps (s) | Temps moyen/scenario (ms) | Status |
|-------|-----------|-----------|---------------------------|--------|
| Behat_critical | 100 | ~5 | ~50 | ✅ DRY_RUN |

## Recommandations

### Pour les testsuites qui timeout
1. **PIM_Integration_Test** (3146 tests): 
   - Nécessite une exécution parallèle ou un timeout beaucoup plus long
   - Estimation: ~62 minutes (3146 tests × 1.18s/test)
   - Considérer l'exécution par sous-répertoires
   
2. **End_to_End** (1182 tests):
   - Nécessite un timeout plus long
   - Estimation: ~23 minutes (1182 tests × 1.18s/test)
   - Tests critiques à exécuter séparément
   
3. **Akeneo_Connectivity_Connection_Integration** (236 tests):
   - Nécessite un timeout plus long
   - Estimation: ~5 minutes (236 tests × 1.18s/test) - Proche de la limite
   - Analyser les causes de lenteur (peut-être des problèmes de fixtures)

### Optimisations possibles
- Exécution parallèle des testsuites indépendantes
- Cache de fixtures pour réduire le temps de setup
- Exécution sélective des tests critiques uniquement

## Notes

- Les testsuites qui dépassent 5 minutes seront arrêtées et marquées comme "TIMEOUT"
- Les erreurs seront documentées mais ne bloqueront pas l'analyse de performance
- **PIM_Integration_Test** est la plus grande suite et nécessite une stratégie d'exécution adaptée
- Les temps moyens sont calculés uniquement pour les suites complétées avec succès
- Behat dry-run est très rapide (~50ms/scenario) mais l'exécution réelle sera beaucoup plus longue
