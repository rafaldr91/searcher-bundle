<?php
/**
 * Created by Vibbe.
 * User: Rafał Drożdżal (rafal@vibbe.pl)
 * Date: 27.01.2021
 */

namespace Vibbe\Searcher\Interfaces;


interface ResultDecoratorInterface
{
    public function decorate(array $results);
}