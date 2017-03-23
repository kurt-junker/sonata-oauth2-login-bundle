<?php

namespace Exozet\Oauth2LoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('exozet_oauth2_login');
        $rootNode
            ->children()
                ->arrayNode('default_user_roles')
                    ->info('Used for user creation after success validation')
                    ->example('ROLE_SONATA_ADMIN')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('valid_email_domains')
                    ->info('Needed for email domain validation')
                    ->example('@exozet.com')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
