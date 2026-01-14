<?php

declare(strict_types=1);

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Akeneo\Platform\Bundle\ImportExportBundle\Infrastructure\StorageClient;

use Akeneo\Platform\Bundle\ImportExportBundle\Domain\StorageClientInterface;
use League\Flysystem\FilesystemOperator;

class FileSystemStorageClient implements StorageClientInterface
{
    public function __construct(private FilesystemOperator $filesystemOperator)
    {
    }

    #[\Override]
    public function fileExists(string $filePath): bool
    {
        return $this->filesystemOperator->fileExists($filePath);
    }

    /**
     * @return resource
     */
    #[\Override]
    public function readStream(string $filePath)
    {
        return $this->filesystemOperator->readStream($filePath);
    }

    /**
     * @param resource $content
     */
    #[\Override]
    public function writeStream(string $filePath, $content): void
    {
        $this->filesystemOperator->writeStream($filePath, $content);
    }

    #[\Override]
    public function getFileSize(string $filePath): int
    {
        return $this->filesystemOperator->fileSize($filePath);
    }

    #[\Override]
    public function move(string $sourceFilePath, string $destinationFilePath): void
    {
        $this->filesystemOperator->move($sourceFilePath, $destinationFilePath);
    }

    #[\Override]
    public function delete(string $filePath): void
    {
        $this->filesystemOperator->delete($filePath);
    }
}
