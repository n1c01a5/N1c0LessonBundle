<?php

namespace N1c0\LessonBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('n1c0_lesson')
            ->children()
            
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->end()
                ->scalarNode('model_manager_name')->defaultNull()->end()

                ->arrayNode('form')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('lesson')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('n1c0_lesson_lesson')->end()
                                ->scalarNode('name')->defaultValue('n1c0_lesson_lesson')->end()
                            ->end()
                        ->end()
                        ->arrayNode('chapter')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('n1c0_lesson_chapter')->end()
                                ->scalarNode('name')->defaultValue('n1c0_lesson_chapter')->end()
                            ->end()
                        ->end()
                        ->arrayNode('conclusion')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('n1c0_lesson_conclusion')->end()
                                ->scalarNode('name')->defaultValue('n1c0_lesson_conslucion')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('class')->isRequired()
                    ->children()
                        ->arrayNode('model')->isRequired()
                            ->children()
                                ->scalarNode('lesson')->isRequired()->end()
                                ->scalarNode('chapter')->isRequired()->end()
                                ->scalarNode('conclusion')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('acl')->end()

                ->arrayNode('acl_roles')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('lesson')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('create')->cannotBeEmpty()->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')->end()
                                ->scalarNode('view')->cannotBeEmpty()->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')->end()
                                ->scalarNode('edit')->cannotBeEmpty()->defaultValue('ROLE_ADMIN')->end()
                                ->scalarNode('delete')->cannotBeEmpty()->defaultValue('ROLE_ADMIN')->end()
                            ->end()
                        ->end()
                        ->arrayNode('chapter')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('create')->cannotBeEmpty()->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')->end()
                                ->scalarNode('view')->cannotBeEmpty()->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')->end()
                                ->scalarNode('edit')->cannotBeEmpty()->defaultValue('ROLE_ADMIN')->end()
                                ->scalarNode('delete')->cannotBeEmpty()->defaultValue('ROLE_ADMIN')->end()
                            ->end()
                        ->end()
                        ->arrayNode('conclusion')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('create')->cannotBeEmpty()->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')->end()
                                ->scalarNode('view')->cannotBeEmpty()->defaultValue('IS_AUTHENTICATED_ANONYMOUSLY')->end()
                                ->scalarNode('edit')->cannotBeEmpty()->defaultValue('ROLE_ADMIN')->end()
                                ->scalarNode('delete')->cannotBeEmpty()->defaultValue('ROLE_ADMIN')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('service')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('manager')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('lesson')->cannotBeEmpty()->defaultValue('n1c0_lesson.manager.lesson.default')->end()
                                ->scalarNode('chapter')->cannotBeEmpty()->defaultValue('n1c0_lesson.manager.chapter.default')->end()
                                ->scalarNode('conclusion')->cannotBeEmpty()->defaultValue('n1c0_lesson.manager.conclusion.default')->end()
                            ->end()
                        ->end()
                        ->arrayNode('acl')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('lesson')->cannotBeEmpty()->defaultValue('n1c0_lesson.acl.lesson.security')->end()
                                ->scalarNode('chapter')->cannotBeEmpty()->defaultValue('n1c0_lesson.acl.chapter.security')->end()
                                ->scalarNode('conclusion')->cannotBeEmpty()->defaultValue('n1c0_lesson.acl.conclusion.security')->end()
                            ->end()
                        ->end()
                        ->arrayNode('form_factory')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('lesson')->cannotBeEmpty()->defaultValue('n1c0_lesson.form_factory.lesson.default')->end()
                                ->scalarNode('chapter')->cannotBeEmpty()->defaultValue('n1c0_lesson.form_factory.chapter.default')->end()
                                ->scalarNode('conclusion')->cannotBeEmpty()->defaultValue('n1c0_lesson.form_factory.conclusion.default')->end()

                            ->end()
                        ->end()
                        ->scalarNode('markup')->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
