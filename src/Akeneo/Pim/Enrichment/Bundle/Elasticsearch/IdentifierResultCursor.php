<?php

declare(strict_types=1);

namespace Akeneo\Pim\Enrichment\Bundle\Elasticsearch;

use Akeneo\Pim\Enrichment\Component\Product\Query\ResultAwareInterface;
use Akeneo\Pim\Enrichment\Component\Product\Query\ResultInterface;
use Akeneo\Tool\Component\StorageUtils\Cursor\CursorInterface;

/**
 * This cursor does not iterate through pages in Elasticsearch, because it's not needed for the datagrid or the API for example.
 *
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class IdentifierResultCursor implements CursorInterface, ResultAwareInterface
{
    /** @var \ArrayIterator */
    private $identifiers;

    /** @var int */
    private $totalCount;

    /** @var ElasticsearchResult */
    private $result;

    public function __construct(array $identifiers, int $totalCount, ElasticsearchResult $result)
    {
        $this->identifiers = new \ArrayIterator($identifiers);
        $this->totalCount = $totalCount;
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function count(): int
    {
        return $this->totalCount;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function current(): mixed
    {
        return $this->identifiers->current();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function key(): mixed
    {
        return $this->identifiers->key();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function next(): void
    {
        $this->identifiers->next();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function rewind(): void
    {
        $this->identifiers->rewind();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function valid(): bool
    {
        return $this->identifiers->valid();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getResult(): ResultInterface
    {
        return $this->result;
    }
}
