<?php


namespace Vibbe\Searcher\Traits;


trait SearchableTrait
{
    public function searchFields() {
        if (property_exists($this, $property = 'searchFields')) {
            return $this->{$property};
        }

        return null;
    }
}
