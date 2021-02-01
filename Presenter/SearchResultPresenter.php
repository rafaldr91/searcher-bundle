<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 27.01.2021
 */

namespace Vibbe\Searcher\Presenter;


use Vibbe\Searcher\Interfaces\ResultDecoratorInterface;

class SearchResultPresenter
{
    public function showResults(ResultDecoratorInterface $decorator, array $results)
    {
        return $decorator->decorate($results);
    }
}