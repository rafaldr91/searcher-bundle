<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 26.01.2021
 */

namespace Vibbe\Searcher\Service;


use http\Exception\RuntimeException;
use Vibbe\Searcher\Builder\Condition\ConditionBuilder;
use Vibbe\Searcher\src\Builder\Condition\EntityConditionBuilder;

class SearcherService implements SearcherServiceInterface
{
    public function handleSearch(string $searchTerm, $comparisonType = ConditionBuilder::COMPARISION_TYPE_WILDCARD): array
    {
        if (!in_array($comparisonType, array(ConditionBuilder::COMPARISION_TYPE_WILDCARD, ConditionBuilder::COMPARISION_TYPE_EQUALS))) {
            throw new RuntimeException("The condition type should be wildcard or equals");
        }

        $conditionBuilder = new EntityConditionBuilder($queryBuilder, $entityName, $searchTerm, $searchFields, $comparisonType);

        return $conditionBuilder->getQueryBuilderWithConditions();
    }
}