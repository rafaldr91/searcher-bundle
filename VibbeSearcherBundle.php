<?php

namespace Vibbe\Searcher;


use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vibbe\Searcher\DependencyInjection\VibbeSearcherBundleExtension;

class VibbeSearcherBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new VibbeSearcherBundleExtension();
    }
}
