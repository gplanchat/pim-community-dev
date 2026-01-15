# Solutions pour corriger les problèmes de configuration des tests

Date: 2026-01-14
Problèmes identifiés: PHPUnit test.service_container et Behat FeatureContext

## Problème 1: PHPUnit - test.service_container manquant

### Symptômes
```
LogicException: Could not find service "test.service_container". Try updating the "framework.test" config to "true".
```

### Analyse
- Le fichier `config/packages/test/framework.yml` existe et contient `framework.test: true`
- Le problème semble être que PHPUnit n'utilise pas l'environnement "test" correctement
- La méthode `TestCase::get()` utilise `static::getContainer()->get($service)` qui devrait fonctionner avec Symfony 5.4

### Solutions proposées

#### Solution 1: Vérifier l'environnement PHPUnit
Le fichier `phpunit.xml` doit définir l'environnement Symfony correctement.

**Action**: Vérifier que `phpunit.xml` charge bien l'environnement "test" via `KERNEL_CLASS`.

**Fichier**: `phpunit.xml`
```xml
<php>
    <server name="KERNEL_CLASS" value="Kernel" force="true" />
    <env name="APP_ENV" value="test" force="true" />
</php>
```

#### Solution 2: Vérifier le bootstrap PHPUnit
Le fichier `config/bootstrap.php` doit initialiser l'environnement correctement.

**Action**: Vérifier que `config/bootstrap.php` définit `$_SERVER['APP_ENV'] = 'test'` si non défini.

#### Solution 3: Utiliser KernelTestCase::getContainer() directement
Au lieu d'utiliser `test.service_container`, utiliser directement `getContainer()` de KernelTestCase.

**Fichier**: `tests/back/Integration/TestCase.php`
```php
protected function get(string $service)
{
    // Utiliser directement getContainer() au lieu de test.service_container
    return static::getContainer()->get($service);
}
```

**Note**: Cette méthode devrait déjà fonctionner avec Symfony 5.4. Le problème pourrait être que `getContainer()` n'est pas initialisé correctement.

#### Solution 4: Vérifier la configuration Symfony FrameworkBundle
S'assurer que le FrameworkBundle est bien configuré pour l'environnement test.

**Fichier**: `config/packages/test/framework.yml` (existe déjà)
```yaml
framework:
    test: true
    session:
        storage_id: session.storage.mock_file
```

**Vérification**: Le fichier existe déjà avec la bonne configuration.

#### Solution 5: Vérifier l'initialisation du Kernel dans les tests
Le Kernel doit être booté avec l'environnement "test".

**Fichier**: `tests/back/Integration/TestCase.php`
```php
protected function setUp(): void
{
    // S'assurer que APP_ENV est défini à 'test'
    $_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'test';
    
    $this->testKernel = static::bootKernel([
        'environment' => 'test',  // Forcer l'environnement
        'debug' => (bool)($_SERVER['APP_DEBUG'] ?? false)
    ]);
    // ...
}
```

### Solution recommandée
**Combinaison de Solution 1 + Solution 5**: 
1. Ajouter `APP_ENV=test` dans `phpunit.xml`
2. Forcer l'environnement dans `TestCase::setUp()`

---

## Problème 2: Behat - FeatureContext non trouvé

### Symptômes
```
`FeatureContext` context class not found and can not be used.
```

### Analyse
- La classe `Context\FeatureContext` existe dans `tests/legacy/features/Context/FeatureContext.php`
- L'autoloading fonctionne (testé avec `class_exists()`)
- Le problème est que Behat ne trouve pas la classe lors de l'exécution

### Solutions proposées

#### Solution 1: Vérifier l'autoloading Composer
S'assurer que le namespace `Context` est bien autoloadé.

**Fichier**: `composer.json`
```json
{
    "autoload": {
        "psr-4": {
            "Context\\": "tests/legacy/features/Context/"
        }
    }
}
```

**Action**: Vérifier si le namespace `Context` est dans l'autoload. Si non, l'ajouter et exécuter `composer dump-autoload`.

#### Solution 2: Vérifier le bootstrap Behat
Le fichier `config/bootstrap.php` doit être chargé correctement.

**Fichier**: `behat.yml`
```yaml
extensions:
    FriendsOfBehat\SymfonyExtension:
        bootstrap: config/bootstrap.php
        kernel:
            path: src/Kernel.php
            class: Kernel
            environment: behat
            debug: false
```

**Vérification**: La configuration semble correcte.

#### Solution 3: Vérifier l'environnement Behat
Behat utilise l'environnement "behat", pas "test". Vérifier que les classes Context sont disponibles dans cet environnement.

**Action**: Vérifier que `config/bootstrap.php` charge bien l'autoloader Composer.

#### Solution 4: Ajouter le namespace Context à l'autoload
Si le namespace n'est pas dans composer.json, l'ajouter explicitement.

**Fichier**: `composer.json`
```json
{
    "autoload": {
        "psr-4": {
            "Context\\": "tests/legacy/features/Context/"
        }
    }
}
```

**Action**: Exécuter `composer dump-autoload` après modification.

#### Solution 5: Vérifier le chemin de FeatureContext dans behat.yml
Le contexte est référencé comme `Context\FeatureContext` dans behat.yml, ce qui est correct.

**Fichier**: `behat.yml`
```yaml
contexts: &context
    -   Context\FeatureContext
```

**Vérification**: La configuration semble correcte.

### Solution recommandée
**Solution 1 + Solution 4**: 
1. Vérifier/ajouter le namespace `Context` dans `composer.json` autoload
2. Exécuter `composer dump-autoload`
3. Vérifier que `config/bootstrap.php` charge bien l'autoloader

---

## Plan d'action

### Étape 1: Corriger PHPUnit
1. [ ] Vérifier `phpunit.xml` - ajouter `APP_ENV=test` si nécessaire
2. [ ] Modifier `tests/back/Integration/TestCase.php` pour forcer l'environnement "test"
3. [ ] Exécuter PHPUnit pour vérifier: `docker compose run --rm php vendor/bin/phpunit --testdox | head -50`

### Étape 2: Corriger Behat
1. [ ] Vérifier `composer.json` - ajouter namespace `Context` si nécessaire
2. [ ] Exécuter `composer dump-autoload`
3. [ ] Vérifier `config/bootstrap.php` charge l'autoloader
4. [ ] Exécuter Behat pour vérifier: `docker compose run --rm php vendor/bin/behat --dry-run`

### Étape 3: Tests de validation
1. [ ] Exécuter PHPUnit sur quelques tests: `docker compose run --rm php vendor/bin/phpunit tests/back/Integration/TestCase.php`
2. [ ] Exécuter Behat avec une suite simple: `docker compose run --rm php vendor/bin/behat --suite=critical --dry-run`

---

## Notes importantes

- Ces problèmes ne sont **PAS liés à PHP 8.4** - ce sont des problèmes de configuration Symfony/Behat
- Les solutions doivent être testées une par une pour identifier la cause exacte
- Une fois corrigés, ces problèmes permettront d'exécuter tous les tests correctement
