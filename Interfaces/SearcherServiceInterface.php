<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 26.01.2021
 */

namespace Vibbe\Searcher\Interfaces;

use Vibbe\Searcher\Builder\Condition\ConditionBuilder;

interface SearcherServiceInterface
{
    public function handleSearch(string $searchTerm, string $comparisonType = ConditionBuilder::COMPARISION_TYPE_WILDCARD): array;
    public function setResultDecorator(ResultDecoratorInterface $decorator): SearcherServiceInterface;
}