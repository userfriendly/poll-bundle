<?php

namespace Userfriendly\Bundle\PollBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root( 'userfriendly_poll' );
        $rootNode
            ->children()
                ->booleanNode( 'enable_anonymous_polling' )->defaultTrue()->end()
                ->scalarNode( 'user_class_registered_polling' )->defaultNull()->end()
            ->end();
        return $treeBuilder;
    }
}
