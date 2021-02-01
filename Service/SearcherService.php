<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 26.01.2021
 */

namespace Vibbe\Searcher\Service;


use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
use Vibbe\Searcher\Builder\Condition\ConditionBuilder;
use Vibbe\Searcher\Builder\Condition\EntityConditionBuilder;
use Vibbe\Searcher\Interfaces\DecoratorInterface;
use Vibbe\Searcher\Interfaces\ResultDecoratorInterface;
use Vibbe\Searcher\Interfaces\Searchable;
use Vibbe\Searcher\Interfaces\SearcherServiceInterface;
use Vibbe\Searcher\Presentation\DefaultResultDecorator;
use Vibbe\Searcher\Presenter\SearchResultPresenter;

class SearcherService implements SearcherServiceInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SearchResultPresenter */
    private $searchResultPresenter;

    /** @var ResultDecoratorInterface */
    private $resultDecorator;

    /**
     * SearcherService constructor.
     */
    public function __construct(EntityManagerInterface $entityManager,
                                SearchResultPresenter $searchResultPresenter, DefaultResultDecorator $resultDecorator)
    {
        $this->entityManager = $entityManager;
        $this->searchResultPresenter = $searchResultPresenter;
        $this->resultDecorator = $resultDecorator;
    }

    public function setResultDecorator(ResultDecoratorInterface $decorator): SearcherServiceInterface
    {
        $this->resultDecorator = $decorator;
        return $this;
    }
    
    public function handleSearch(string $searchTerm, string $comparisonType = ConditionBuilder::COMPARISION_TYPE_WILDCARD): array
    {
        if (!in_array($comparisonType, array(ConditionBuilder::COMPARISION_TYPE_WILDCARD, ConditionBuilder::COMPARISION_TYPE_EQUALS))) {
            throw new RuntimeException("The condition type should be wildcard or equals");
        }

        $results = [];
        /** @var \ReflectionClass $entityRC */
        foreach ($this->getEntities() as $entityRC) {
            $entityRepository = $this->getReflectionClassRepository($entityRC);
            if($entityRepository instanceof Searchable) {
                $queryBuilder = $this->createRepositoryQueryBuilder($entityRepository);
                $conditionBuilder = $this->createEntityConditionBuilder($searchTerm, $comparisonType, $queryBuilder, $entityRepository);

                $results = array_merge($results, $conditionBuilder->getQueryBuilderWithConditions()->getQuery()->getResult());
            }
        }
        return $this->searchResultPresenter->showResults($this->resultDecorator, $results);
    }

    /**
     * @return array
     */
    protected function getEntities(): array
    {
        $entities = [];
        $metas = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($metas as $meta) {
            $entities[] = $meta->getReflectionClass();
        }
        return $entities;
    }

    /**
     * @param \ReflectionClass $entityRC
     * @return \Doctrine\Persistence\ObjectRepository
     */
    protected function getReflectionClassRepository(\ReflectionClass $entityRC): \Doctrine\Persistence\ObjectRepository
    {
        $entityRepository = $this->entityManager->getRepository($entityRC->getName());
        return $entityRepository;
    }

    /**
     * @param $entityRepository
     * @return mixed
     */
    protected function createRepositoryQueryBuilder($entityRepository)
    {
        $queryBuilder = $entityRepository->createQueryBuilder('e');
        return $queryBuilder;
    }

    /**
     * @param string $searchTerm
     * @param $comparisonType
     * @param $queryBuilder
     * @param $entityRepository
     * @return EntityConditionBuilder
     */
    protected function createEntityConditionBuilder(string $searchTerm, $comparisonType, $queryBuilder, $entityRepository): EntityConditionBuilder
    {
        return new EntityConditionBuilder($queryBuilder, $entityRepository->getClassName(), $searchTerm, $entityRepository->searchFields(), $comparisonType);
    }
}