<?php

namespace BiberLtd\Bundles\UnitConverterBundle;

use BiberLtd\Bundles\UnitConverterBundle\DependencyInjection\AutoLoad;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BiberLtdBundlesUnitConverterBundle extends Bundle

{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AutoLoad\LoadRouters());
    }
}
