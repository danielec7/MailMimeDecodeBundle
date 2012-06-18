<?php

namespace Ijanki\Bundle\MailMimeDecodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ijanki_mail_mime_decode');

        $rootNode
              ->children()
                  ->booleanNode('class')
                    ->defaultValue('Ijanki\Bundle\MailMimeDecodeBundle\Util\MailMimeDecode')
                  ->end()
                  ->booleanNode('decode_bodies')
                    ->defaultValue(false)
                  ->end()
                  ->booleanNode('include_bodies')
                    ->defaultValue(true)
                  ->end()
                  ->booleanNode('rfc822_bodies')
                    ->defaultValue(false)
                  ->end()
                  ->booleanNode('decode_headers')
                    ->defaultValue(false)
                  ->end()
              ->end();

        return $treeBuilder;
    }
}
