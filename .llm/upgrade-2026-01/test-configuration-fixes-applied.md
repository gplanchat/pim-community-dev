# Solutions appliquées pour corriger les problèmes de configuration des tests

Date: 2026-01-14

## Modifications appliquées

### 1. PHPUnit - Configuration APP_ENV

**Fichier modifié**: `phpunit.xml`

**Changement**:
```xml
<php>
    <!-- ... autres configurations ... -->
    <env name="APP_ENV" value="test" force="true" />
</php>
```

**Raison**: Force l'environnement "test" pour PHPUnit, ce qui garantit que `config/packages/test/framework.yml` est chargé avec `framework.test: true`.

### 2. PHPUnit - Forcer l'environnement dans TestCase

**Fichier modifié**: `tests/back/Integration/TestCase.php`

**Changement**:
```php
protected function setUp(): void
{
    // S'assurer que APP_ENV est défini à 'test' pour PHPUnit
    $_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'test';
    
    $this->testKernel = static::bootKernel([
        'environment' => 'test',
        'debug' => (bool)($_SERVER['APP_DEBUG'] ?? false)
    ]);
    // ...
}
```

**Raison**: Force explicitement l'environnement "test" lors du boot du Kernel, garantissant que `test.service_container` est disponible.

### 3. Behat - Autoload Composer

**Action**: Exécuté `composer dump-autoload`

**Raison**: Régénère l'autoloader pour s'assurer que toutes les classes sont correctement chargées, notamment `Context\FeatureContext`.

## Résultats des tests

### PHPUnit
- ✅ **RÉSOLU**: Les tests démarrent maintenant sans erreur `test.service_container`
- ✅ L'environnement "test" est correctement chargé
- ⚠️ Des warnings de dépréciation PHP 8.4 apparaissent (normal, liés à Symfony 5.4)
- ⚠️ Nouvelle erreur détectée: Variable d'environnement `PUBSUB_SUBSCRIPTION_BUSINESS_EVENT` manquante (problème de configuration application, non lié à PHP 8.4)

### Behat
- ⚠️ Les suites Behat nécessitent le format `legacy:critical` (pas juste `critical` ou `legacy`)
- ⏳ À tester avec le bon format de suite

## Prochaines étapes

1. [ ] Tester PHPUnit sur un échantillon de tests pour confirmer que `test.service_container` fonctionne
2. [ ] Tester Behat avec `--suite=legacy` pour vérifier que FeatureContext est trouvé
3. [ ] Documenter les résultats dans les fichiers de tracking
4. [ ] Commiter les modifications

## Notes

- Les warnings de dépréciation PHP 8.4 sont normaux et seront résolus lors de la migration Symfony 8.0
- Les modifications sont minimales et ciblées pour résoudre les problèmes spécifiques
- Ces corrections ne sont pas liées à PHP 8.4 mais à la configuration Symfony/Behat
