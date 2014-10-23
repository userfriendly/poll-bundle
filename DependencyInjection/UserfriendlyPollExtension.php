<?php

namespace Userfriendly\Bundle\PollBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UserfriendlyPollExtension extends Extension implements PrependExtensionInterface
{
    public function prepend( ContainerBuilder $container )
    {
        ////////////////////////////////////////////////////////
        // Get registered bundles, ensure the ones we require //
        // are there, and read configuration for this bundle. //
        ////////////////////////////////////////////////////////
        $bundles = $container->getParameter('kernel.bundles');
        $deps = array( 'StofDoctrineExtensionsBundle' );
        foreach ( $deps as $dep )
        {
            if ( !isset( $bundles[$dep] ))
            {
                throw new \Exception( 'You must enable the ' . $dep . ' in your AppKernel!' );
            }
        }
        $configs = $container->getExtensionConfig( $this->getAlias() );
        $config = $this->processConfiguration( new Configuration(), $configs );
        ////////////////////////////////////////////
        // Pass configuration on to other bundles //
        ////////////////////////////////////////////
        $doctrineExtensionsConfig = array( 'orm' => array( 'default' => array(
                                        'timestampable' => true,
                                        'sluggable' => true,
                                        'sortable' => true,
                                    )));
        $container->prependExtensionConfig( 'stof_doctrine_extensions', $doctrineExtensionsConfig );
        if ( isset( $config['user_class_registered_polling'] ) && $config['user_class_registered_polling'] )
        {
            $container->prependExtensionConfig(
                'doctrine', array( 'orm' => array(
                    'resolve_target_entities' => array(
                            'Userfriendly\Bundle\PollBundle\Model\UserInterface' => $config['user_class_registered_polling'],
                    )
                ))
            );
        }
    }

    public function load( array $configs, ContainerBuilder $container )
    {
        /////////////////////////////////////////////
        // Process configuration and load services //
        /////////////////////////////////////////////
        $configuration = new Configuration();
        $config = $this->processConfiguration( $configuration, $configs );
        $loader = new YamlFileLoader( $container, new FileLocator( __DIR__ . '/../Resources/config' ));
        $loader->load( 'services.yml' );
        //////////////////////////////////
        // Set configuration parameters //
        //////////////////////////////////
        $configParams = array();
        $configParams['allow_changing_vote'] = isset( $config['allow_changing_vote'] ) && $config['allow_changing_vote'];
        // TODO: use the configuration class properly instead of doing the following:
        if ( array_key_exists( 'threshold', $config ))
        {
            $configParams['threshold'] = $config['threshold'];
        }
        else
        {
            $configParams['threshold'] = array(
                                             'timespan' => 1,
                                             'votes_allowed' => 1,
                                             'timeout' => 1,
                                             'grace_period' => 1,
                                         );
        }
        // ...
        $configParams['anonymous_polling'] = isset( $config['enable_anonymous_polling'] ) && $config['enable_anonymous_polling'];
        $configParams['registered_polling'] = isset( $config['user_class_registered_polling'] ) && $config['user_class_registered_polling'];
        if ( !$configParams['anonymous_polling'] && !$configParams['registered_polling'] )
        {
            throw new \Exception( 'Configuration error: you must enable anonymous polling or define a user class!' );
        }
        $container->setParameter( 'uf.poll.config', $configParams );
    }
}
