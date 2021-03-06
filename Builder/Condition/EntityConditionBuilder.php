<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 26.01.2021
 */

namespace Vibbe\Searcher\Builder\Condition;

use Doctrine\ORM\QueryBuilder;
use Vibbe\Searcher\Builder\Condition\ConditionBuilder;

class EntityConditionBuilder extends ConditionBuilder
{
    /**
     *
     * @param \Petkopara\MultiSearchBundle\Search\Condition\QueryBuilder $queryBuilder
     * @param string $entityName
     * @param string $searchTerm
     * @param array $searchFields
     * @param string $comparisonType WILDCARD or EQUALS
     */
    public function __construct(QueryBuilder $queryBuilder, $entityName, $searchTerm, array $searchFields = array(), $comparisonType = self::COMPARISION_TYPE_WILDCARD)
    {
        $this->entityManager = $queryBuilder->getEntityManager();

        $this->queryBuilder = $queryBuilder;
        $this->searchTerm = $searchTerm;
        $this->searchComparisonType = $comparisonType;
        $this->entityName = $entityName;


        /** @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
        $metadata = $this->entityManager->getClassMetadata($this->entityName);

        $this->idName = $metadata->getSingleIdentifierFieldName();

        if (count($searchFields) > 0) {
            $this->searchColumns = $searchFields;
        } else {
            foreach ($metadata->fieldMappings as $field) {
                $this->searchColumns[] = $field['fieldName'];
            }
        }
    }
}