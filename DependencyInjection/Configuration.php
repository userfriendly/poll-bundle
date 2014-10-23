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
                ->booleanNode( 'allow_changing_vote' )->defaultFalse()->end()
                ->booleanNode( 'enable_anonymous_polling' )->defaultTrue()->end()
                ->scalarNode( 'user_class_registered_polling' )->defaultNull()->end()
                ->arrayNode( 'threshold' )
                    ->children()
                        ->scalarNode( 'timespan' )->defaultValue( 1 )->end()
                        ->scalarNode( 'votes_allowed' )->defaultValue( 1 )->end()
                        ->scalarNode( 'timeout' )->defaultValue( 1 )->end()
                        ->scalarNode( 'grace_period' )->defaultValue( 1 )->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
