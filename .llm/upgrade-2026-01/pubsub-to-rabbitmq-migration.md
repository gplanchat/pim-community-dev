# Migration PubSub → RabbitMQ pour les tests

Date: 2026-01-14
Objectif: Remplacer Google PubSub par RabbitMQ pour les environnements test et behat

## Contexte

PubSub nécessite des variables d'environnement Google Cloud qui ne sont pas disponibles dans l'environnement de test local. RabbitMQ est une alternative open-source qui fonctionne parfaitement avec Symfony Messenger.

## Modifications appliquées

### 1. Ajout du type de transport RABBITMQ

**Fichier**: `src/Akeneo/Tool/Component/Messenger/Config/TransportType.php`
- Ajout de `case RABBITMQ = 'RABBITMQ';` dans l'enum TransportType

### 2. Configuration Messenger

**Fichier**: `config/packages/messenger.php`
- Changement: `'behat', 'test' => TransportType::RABBITMQ` (au lieu de `TransportType::PUB_SUB`)

### 3. Ajout de la méthode createRabbitMQTransport

**Fichier**: `src/Akeneo/Tool/Bundle/MessengerBundle/Config/MessengerConfigBuilder.php`
- Ajout de la méthode `createRabbitMQTransport()` qui crée une configuration RabbitMQ
- Ajout de `TransportType::RABBITMQ` dans le match statement

### 4. Dépendance Composer

**Fichier**: `composer.json`
- Ajout de `"symfony/amqp-messenger": "^5.4.0"`

### 5. Docker Compose

**Fichier**: `docker-compose.yml`
- Ajout du service RabbitMQ:
  ```yaml
  rabbitmq:
    image: 'rabbitmq:3.12-management-alpine'
    environment:
      RABBITMQ_DEFAULT_USER: '${RABBITMQ_USER:-guest}'
      RABBITMQ_DEFAULT_PASS: '${RABBITMQ_PASSWORD:-guest}'
    ports:
      - '${DOCKER_PORT_RABBITMQ:-5672}:5672'
      - '${DOCKER_PORT_RABBITMQ_MANAGEMENT:-15672}:15672'
  ```

## Configuration RabbitMQ

### DSN par défaut
- Format: `amqp://guest:guest@rabbitmq:5672/%2f/messages`
- Peut être surchargé via variable d'environnement `MESSENGER_TRANSPORT_DSN`

### Exchange et Queues
- Exchange: `messages` (type: direct)
- Queues: Une queue par consumer avec binding key = nom de la queue
- Auto-setup: Activé pour dev, test, test_fake, behat

## Avantages

1. ✅ **Open-source**: Pas de dépendance Google Cloud
2. ✅ **Local**: Fonctionne sans credentials cloud
3. ✅ **Standard**: Support natif Symfony Messenger
4. ✅ **Compatible**: Même API que les autres transports Messenger

## Prochaines étapes

1. [ ] Installer la dépendance: `composer require symfony/amqp-messenger:^5.4`
2. [ ] Démarrer RabbitMQ: `docker compose up -d rabbitmq`
3. [ ] Tester la configuration: Exécuter PHPUnit/Behat
4. [ ] Vérifier que les messages sont bien envoyés/reçus via RabbitMQ

## Notes

- PubSub reste disponible pour les environnements de production si nécessaire
- La migration ne concerne que les environnements test/behat
- Les environnements dev/prod continuent d'utiliser Doctrine (par défaut)
