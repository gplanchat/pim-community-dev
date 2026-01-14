<?php

declare(strict_types=1);

/*
 * @copyright 2021 Akeneo SAS (https://www.akeneo.com)
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Akeneo\Tool\Bundle\ElasticsearchBundle\Infrastructure\Client;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Webmozart\Assert\Assert;

final class ClientMigration implements ClientMigrationInterface
{
    private Client $client;

    public function __construct(ClientBuilder $clientBuilder, array $hosts)
    {
        $this->client = $clientBuilder->setHosts($hosts)->build();
    }

    #[\Override]
    public function aliasExist(string $indexAlias): bool
    {
        return $this->client->indices()->existsAlias(['name' => $indexAlias]);
    }

    #[\Override]
    public function getIndexNameFromAlias(string $indexAlias): array
    {
        $aliases = $this->client->indices()->getAlias(['name' => $indexAlias]);

        return \array_keys($aliases);
    }

    #[\Override]
    public function reindex(string $sourceIndexAlias, string $targetIndexAlias, array $query)
    {
        $reindexResponse = $this->client->reindex([
            'wait_for_completion' => true,
            'body' => [
                "source" => [
                    "index" => $sourceIndexAlias,
                    "query" => $query,
                ],
                "dest" => [
                    "index" => $targetIndexAlias,
                ]
            ]
        ]);

        return $reindexResponse["total"];
    }

    #[\Override]
    public function removeIndex(string $indexName): void
    {
        $this->assertResponseIsAcknowledged($this->client->indices()->delete(['index' => $indexName]));
    }

    #[\Override]
    public function getIndexSettings(string $index): array
    {
        $indicesClient = $this->client->indices();
        $settingsResponse = $indicesClient->getSettings(['index' => $index]);

        return $settingsResponse[$index]['settings']['index'];
    }

    #[\Override]
    public function putIndexSetting(string $indexName, array $indexSettings)
    {
        $indicesClient = $this->client->indices();

        $this->assertResponseIsAcknowledged($indicesClient->putSettings([
            'index' => $indexName,
            'body' => [
                'index' => $indexSettings
            ]
        ]));
    }

    #[\Override]
    public function switchIndexAlias(string $oldIndexAlias, string $oldIndexName, string $newIndexAlias, string $newIndexName): void
    {
        $this->assertResponseIsAcknowledged(
            $this->client->indices()->updateAliases([
                'body' => [
                    'actions' => [
                        [
                            'add' => [
                                'alias' => $oldIndexAlias,
                                'index' => $newIndexName,
                            ],
                        ],
                        [
                            'remove' => [
                                'alias' => $oldIndexAlias,
                                'index' => $oldIndexName,
                            ],
                        ],
                        [
                            'add' => [
                                'alias' => $newIndexAlias,
                                'index' => $oldIndexName,
                            ]
                        ],
                        [
                            'remove' => [
                                'alias' => $newIndexAlias,
                                'index' => $newIndexName,
                            ]
                        ],
                    ]
                ]
            ])
        );
    }

    #[\Override]
    public function createAlias(string $indexAlias, string $indexName): void
    {
        $this->assertResponseIsAcknowledged(
            $this->client->indices()->updateAliases([
                'body' => [
                    'actions' => [
                        [
                            'add' => [
                                'alias' => $indexAlias,
                                'index' => $indexName,
                            ],
                        ],
                    ],
                ]
            ])
        );
    }

    #[\Override]
    public function renameAlias(string $oldIndexAlias, string $newIndexAlias, string $indexName): void
    {
        $this->assertResponseIsAcknowledged(
            $this->client->indices()->updateAliases([
                'body' => [
                    'actions' => [
                        [
                            'add' => [
                                'alias' => $newIndexAlias,
                                'index' => $indexName,
                            ],
                        ],
                        [
                            'remove' => [
                                'alias' => $oldIndexAlias,
                                'index' => $indexName,
                            ],
                        ],
                    ]
                ]
            ])
        );
    }

    #[\Override]
    public function createIndex(string $indexName, array $body): void
    {
        $indicesClient = $this->client->indices();

        $this->assertResponseIsAcknowledged(
            $indicesClient->create([
                'index' => $indexName,
                'body' => $body
            ])
        );
    }

    #[\Override]
    public function refreshIndex(string $indexName): void
    {
        $indicesClient = $this->client->indices();

        $indicesClient->refresh(['index' => $indexName]);
    }

    private function assertResponseIsAcknowledged(array $response): void
    {
        Assert::true($response['acknowledged']);
    }
}
