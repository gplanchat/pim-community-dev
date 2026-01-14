<?php

declare(strict_types=1);

namespace Akeneo\Connectivity\Connection\Tests\PhpStan\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

final class CoverQueryByIntegrationTestRule implements Rule
{
    private const string ERROR_MESSAGE = 'Query class must be covered by Integration test';

    #[\Override]
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (
            1 === \preg_match('~Akeneo\\\Connectivity\\\Connection\\\Infrastructure\\\(([^\\\]+\\\)*Persistence(\\\.+)*)~', $scope->getNamespace(), $matches)
            && \str_ends_with((string) $node->getOriginalNode()->name, 'Query')
        ) {
            $integrationTestClass = \sprintf(
                '\\Akeneo\\Connectivity\\Connection\\Tests\\Integration\\%s\\%sIntegration',
                $matches[1],
                (string) $node->getOriginalNode()->name,
            );

            if (!\class_exists($integrationTestClass)) {
                return [self::ERROR_MESSAGE];
            }
        }

        return [];
    }
}
