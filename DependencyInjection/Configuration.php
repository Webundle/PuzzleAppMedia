<?php

namespace Puzzle\App\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $rootNode = $treeBuilder->root('puzzle_app_media');

        $rootNode
            ->children()
                ->scalarNode('title')->defaultValue('media.title')->end()
                ->scalarNode('description')->defaultValue('media.description')->end()
                ->scalarNode('icon')->defaultValue('media.icon')->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('file')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('list')->defaultValue('PuzzleAppMediaBundle:File:list.html.twig')->end()
                                ->scalarNode('show')->defaultValue('PuzzleAppMediaBundle:File:show.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('folder')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('list')->defaultValue('PuzzleAppMediaBundle:Folder:list.html.twig')->end()
                                ->scalarNode('show')->defaultValue('PuzzleAppMediaBundle:Folder:show.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
