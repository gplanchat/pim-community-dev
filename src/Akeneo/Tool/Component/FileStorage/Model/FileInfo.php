<?php

namespace Akeneo\Tool\Component\FileStorage\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File.
 *
 * @author    Julien Janvier <jjanvier@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FileInfo implements FileInfoInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $key;

    /** @var string */
    protected $originalFilename;

    /** @var string */
    protected $mimeType;

    /** @var int */
    protected $size;

    /** @var string */
    protected $extension;

    /** @var string */
    protected $hash;

    /** @var string */
    protected $storage;

    /** @var bool */
    protected $removed = false;

    /** @var UploadedFile */
    protected $uploadedFile;

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setStorage($storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUploadedFile(?UploadedFile $uploadedFile = null)
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isRemoved()
    {
        return $this->removed;
    }

    /**
     * @return string
     */
    #[\Override]
    public function __toString()
    {
        return $this->getOriginalFilename();
    }
}
