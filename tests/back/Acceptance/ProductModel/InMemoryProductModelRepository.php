<?php

declare(strict_types=1);

namespace Akeneo\Test\Acceptance\ProductModel;

use Akeneo\Pim\Enrichment\Component\Product\Model\ProductModelInterface;
use Akeneo\Pim\Enrichment\Component\Product\Repository\ProductModelRepositoryInterface;
use Akeneo\Pim\Structure\Component\Model\FamilyVariantInterface;
use Akeneo\Test\Acceptance\Common\NotImplementedException;
use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Doctrine\Common\Collections\ArrayCollection;

class InMemoryProductModelRepository implements IdentifiableObjectRepositoryInterface, SaverInterface, ProductModelRepositoryInterface
{
    /** @var ArrayCollection */
    private $productModels;

    public function __construct(array $productModels = [])
    {
        $this->productModels = new ArrayCollection($productModels);
    }

    #[\Override]
    public function getIdentifierProperties()
    {
        return ['code'];
    }

    #[\Override]
    public function findOneByIdentifier($identifier)
    {
        return $this->productModels->get($identifier);
    }

    #[\Override]
    public function save($object, array $options = [])
    {
        if (!$object instanceof ProductModelInterface) {
            throw new \InvalidArgumentException('The object argument should be a ProductModel');
        }

        $this->productModels->set($object->getCode(), $object);
    }

    #[\Override]
    public function findFirstCreatedVariantProductModel(ProductModelInterface $productModel): ?ProductModelInterface
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function getItemsFromIdentifiers(array $identifiers)
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function find($id)
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findAll()
    {
        return $this->productModels->toArray();
    }

    #[\Override]
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findOneBy(array $criteria)
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function getClassName()
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findSiblingsProductModels(ProductModelInterface $productModel): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function countRootProductModels(): int
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findChildrenProductModels(ProductModelInterface $productModel): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findDescendantProductIdentifiers(ProductModelInterface $productModel): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findByIdentifiers(array $codes): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findChildrenProducts(ProductModelInterface $productModel): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function searchRootProductModelsAfter(?ProductModelInterface $product, int $limit): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findSubProductModels(FamilyVariantInterface $familyVariant): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findRootProductModels(FamilyVariantInterface $familyVariant): array
    {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function findProductModelsForFamilyVariant(
        FamilyVariantInterface $familyVariant,
        ?string $search = null,
        int $limit = 20,
        int $page = 1
    ): array {
        throw new NotImplementedException(__METHOD__);
    }

    #[\Override]
    public function searchLastLevelByCode(
        FamilyVariantInterface $familyVariant,
        string $search,
        int $limit,
        int $page = 0
    ): array {
        throw new NotImplementedException(__METHOD__);
    }
}
