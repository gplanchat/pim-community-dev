# Plan pour contourner le problème des variables d'environnement PubSub manquantes

Date: 2026-01-14

## Problème identifié

Les fichiers `config/packages/test/messenger.yml` et `config/packages/behat/messenger.yml` utilisent des variables d'environnement PubSub qui ne sont pas définies :
- `PUBSUB_SUBSCRIPTION_BUSINESS_EVENT`
- `PUBSUB_TOPIC_BUSINESS_EVENT`
- `GOOGLE_CLOUD_PROJECT`
- `PUBSUB_AUTO_SETUP`
- Et d'autres variables PubSub...

## Analyse

1. **Configuration actuelle** :
   - `config/packages/messenger.php` utilise `TransportType::PUB_SUB` pour les environnements `test` et `behat`
   - Les fichiers YAML statiques (`test/messenger.yml`, `behat/messenger.yml`) utilisent directement PubSub avec des variables d'environnement obligatoires
   - `MessengerConfigBuilder` utilise déjà `default::string:` pour les variables générées dynamiquement, mais pas pour les fichiers YAML statiques

2. **Options disponibles** :
   - Option A: Utiliser des valeurs par défaut dans les fichiers YAML avec la syntaxe Symfony `%env(default::string:VAR_NAME:default_value)%`
   - Option B: Changer le transport pour utiliser `in-memory://` pour les tests (comme `test_fake`)
   - Option C: Définir les variables d'environnement dans docker-compose.yml ou .env
   - Option D: Utiliser un transport sync pour les tests

## Solution recommandée : Option A + Option B (hybride)

**Stratégie** : Utiliser des valeurs par défaut dans les fichiers YAML ET permettre de basculer vers in-memory si nécessaire.

### Étape 1: Ajouter des valeurs par défaut dans les fichiers YAML

Modifier `config/packages/test/messenger.yml` et `config/packages/behat/messenger.yml` pour utiliser la syntaxe Symfony avec valeurs par défaut :

```yaml
framework:
    messenger:
        transports:
            business_event:
                dsn: 'gps:'
                options:
                    project_id: '%env(default::string:GOOGLE_CLOUD_PROJECT:test-project)%'
                    topic_name: '%env(default::string:PUBSUB_TOPIC_BUSINESS_EVENT:test-business-event-topic)%'
                    subscription_name: '%env(default::string:PUBSUB_SUBSCRIPTION_BUSINESS_EVENT:test-business-event-subscription)%'
                    auto_setup: '%env(default::bool:PUBSUB_AUTO_SETUP:false)%'
```

### Étape 2: Alternative - Utiliser in-memory pour les tests

Modifier `config/packages/messenger.php` pour utiliser `IN_MEMORY` pour `test` au lieu de `PUB_SUB` :

```php
$transportType = match ($containerConfigurator->env()) {
    'behat' => TransportType::PUB_SUB,  // Garder PubSub pour Behat si nécessaire
    'test', 'test_fake' => TransportType::IN_MEMORY,  // Utiliser in-memory pour tests
    default => TransportType::DOCTRINE,
};
```

**Avantage** : Les tests n'ont plus besoin de configuration PubSub du tout.

### Étape 3: Définir les variables dans docker-compose.yml (solution temporaire)

Ajouter les variables d'environnement dans `docker-compose.yml` pour les containers de test :

```yaml
php:
    environment:
        # ... autres variables ...
        PUBSUB_SUBSCRIPTION_BUSINESS_EVENT: 'test-business-event-subscription'
        PUBSUB_TOPIC_BUSINESS_EVENT: 'test-business-event-topic'
        GOOGLE_CLOUD_PROJECT: 'test-project'
        PUBSUB_AUTO_SETUP: 'false'
```

## Plan d'action recommandé

### Phase 1: Solution rapide (Option C)
1. Ajouter les variables d'environnement dans `docker-compose.yml`
2. Tester que les tests fonctionnent
3. Commiter la solution temporaire

### Phase 2: Solution propre (Option A)
1. Modifier les fichiers YAML pour utiliser des valeurs par défaut
2. Tester que les tests fonctionnent sans variables d'environnement
3. Commiter la solution définitive

### Phase 3: Solution optimale (Option B - optionnel)
1. Modifier `config/packages/messenger.php` pour utiliser `IN_MEMORY` pour `test`
2. Tester que les tests fonctionnent avec in-memory
3. Commiter si cela améliore les performances des tests

## Recommandation finale

**Commencer par Phase 1 (Option C)** pour débloquer rapidement les tests, puis **implémenter Phase 2 (Option A)** pour une solution plus propre et maintenable.
