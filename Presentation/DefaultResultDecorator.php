<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 27.01.2021
 */

namespace Vibbe\Searcher\Presentation;


use Vibbe\Searcher\Interfaces\ResultDecoratorInterface;

class DefaultResultDecorator implements ResultDecoratorInterface
{
    public function decorate(array $results)
    {
        return $results;
    }
}