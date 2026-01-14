# Solutions appliquées pour contourner les variables d'environnement PubSub manquantes

Date: 2026-01-14

## Problème résolu

Les tests PHPUnit et Behat échouaient avec l'erreur :
```
Environment variable not found: "PUBSUB_SUBSCRIPTION_BUSINESS_EVENT"
```

## Solutions appliquées

### Phase 1: Ajout des variables dans docker-compose.yml ✅

**Fichier modifié**: `docker-compose.yml`

**Changements**:
- Ajout de toutes les variables PubSub nécessaires dans les containers `php` et `httpd`
- Valeurs par défaut définies pour l'environnement de test
- Variables utilisent la syntaxe `${VAR:-default}` pour permettre la surcharge

**Variables ajoutées**:
- `GOOGLE_CLOUD_PROJECT` (default: `test-project`)
- `PUBSUB_AUTO_SETUP` (default: `false`)
- `PUBSUB_TOPIC_BUSINESS_EVENT` (default: `test-business-event-topic`)
- `PUBSUB_SUBSCRIPTION_BUSINESS_EVENT` (default: `test-business-event-subscription`)
- `PUBSUB_SUBSCRIPTION_WEBHOOK` (default: `test-webhook-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_UI` (default: `test-ui-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_UI` (default: `test-ui-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_IMPORT_EXPORT` (default: `test-import-export-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_IMPORT_EXPORT` (default: `test-import-export-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_DATA_MAINTENANCE` (default: `test-data-maintenance-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_DATA_MAINTENANCE` (default: `test-data-maintenance-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_SCHEDULED_JOB` (default: `test-scheduled-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_SCHEDULED_JOB` (default: `test-scheduled-job-subscription`)
- `PUBSUB_TOPIC_JOB_QUEUE_PAUSED_JOB` (default: `test-paused-job-topic`)
- `PUBSUB_SUBSCRIPTION_JOB_QUEUE_PAUSED_JOB` (default: `test-paused-job-subscription`)

### Phase 2: Ajout de valeurs par défaut dans les fichiers YAML ✅

**Fichiers modifiés**:
- `config/packages/test/messenger.yml`
- `config/packages/behat/messenger.yml`

**Changements**:
- Remplacement de `%env(VAR_NAME)%` par `%env(default::string:VAR_NAME:default_value)%`
- Remplacement de `%env(bool:VAR_NAME)%` par `%env(default::bool:VAR_NAME:false)%`
- Permet aux tests de fonctionner même si les variables ne sont pas définies dans l'environnement

**Exemple de changement**:
```yaml
# Avant
project_id: '%env(GOOGLE_CLOUD_PROJECT)%'

# Après
project_id: '%env(default::string:GOOGLE_CLOUD_PROJECT:test-project)%'
```

## Résultats

### PHPUnit
- ✅ **RÉSOLU**: Plus d'erreur "Environment variable not found"
- ✅ Les tests démarrent correctement
- ⚠️ Des erreurs de tests subsistent (non liées à la configuration PubSub)

### Behat
- ✅ **RÉSOLU**: Plus d'erreur "Environment variable not found"
- ✅ FeatureContext est trouvé et fonctionne
- ✅ Les features et scenarios sont détectés correctement

## Avantages de la solution

1. **Double protection**: Les variables sont définies dans docker-compose.yml ET ont des valeurs par défaut dans les fichiers YAML
2. **Flexibilité**: Les valeurs peuvent être surchargées via variables d'environnement
3. **Maintenabilité**: Les valeurs par défaut sont documentées dans les fichiers de configuration
4. **Compatibilité**: Fonctionne avec ou sans émulateur PubSub

## Notes

- Ces modifications ne sont pas liées à PHP 8.4 mais à la configuration Symfony Messenger/PubSub
- Les valeurs par défaut sont spécifiques aux tests et ne doivent pas être utilisées en production
- L'émulateur PubSub (`pubsub-emulator`) est disponible dans docker-compose.yml pour les tests locaux
