<?php

/**
 * This file is chapter of the N1c0LessonBundle package.
 *
 * (c) 
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace N1c0\LessonBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class N1c0LessonExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (array_key_exists('acl', $config)) {
            $this->loadAcl($container, $config);
        }

        $container->setParameter('n1c0_lesson.model.lesson.class', $config['class']['model']['lesson']);
        $container->setParameter('n1c0_lesson.model.introduction.class', $config['class']['model']['introduction']);
        $container->setParameter('n1c0_lesson.model.chapter.class', $config['class']['model']['chapter']);
        $container->setParameter('n1c0_lesson.model.argument.class', $config['class']['model']['argument']);
        $container->setParameter('n1c0_lesson.model.conclusion.class', $config['class']['model']['conclusion']);

        $container->setParameter('n1c0_lesson.model_manager_name', $config['model_manager_name']);

        $container->setParameter('n1c0_lesson.form.lesson.type', $config['form']['lesson']['type']);
        $container->setParameter('n1c0_lesson.form.introduction.type', $config['form']['introduction']['type']);
        $container->setParameter('n1c0_lesson.form.chapter.type', $config['form']['chapter']['type']);
        $container->setParameter('n1c0_lesson.form.argument.type', $config['form']['argument']['type']);
        $container->setParameter('n1c0_lesson.form.conclusion.type', $config['form']['conclusion']['type']);

        $container->setParameter('n1c0_lesson.form.lesson.name', $config['form']['lesson']['name']);
        $container->setParameter('n1c0_lesson.form.introduction.name', $config['form']['introduction']['name']);
        $container->setParameter('n1c0_lesson.form.chapter.name', $config['form']['chapter']['name']);
        $container->setParameter('n1c0_lesson.form.argument.name', $config['form']['argument']['name']);
        $container->setParameter('n1c0_lesson.form.conclusion.name', $config['form']['conclusion']['name']);

        $container->setAlias('n1c0_lesson.form_factory.lesson', $config['service']['form_factory']['lesson']);
        $container->setAlias('n1c0_lesson.form_factory.introduction', $config['service']['form_factory']['introduction']);
        $container->setAlias('n1c0_lesson.form_factory.chapter', $config['service']['form_factory']['chapter']);
        $container->setAlias('n1c0_lesson.form_factory.argument', $config['service']['form_factory']['argument']);
        $container->setAlias('n1c0_lesson.form_factory.conclusion', $config['service']['form_factory']['conclusion']);

        $container->setAlias('n1c0_lesson.manager.lesson', $config['service']['manager']['lesson']);
        $container->setAlias('n1c0_lesson.manager.introduction', $config['service']['manager']['introduction']);
        $container->setAlias('n1c0_lesson.manager.chapter', $config['service']['manager']['chapter']);
        $container->setAlias('n1c0_lesson.manager.argument', $config['service']['manager']['argument']);
        $container->setAlias('n1c0_lesson.manager.conclusion', $config['service']['manager']['conclusion']);

        // Add a condition if markup so...
        $container->setAlias('n1c0_lesson.markup', new Alias($config['service']['markup'], false));
    }

    protected function loadAcl(ContainerBuilder $container, array $config)
    {
        //$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        //$loader->load('acl.xml');

        foreach (array(1 => 'create', 'view', 'edit', 'delete') as $index => $perm) {
            $container->getDefinition('n1c0_lesson.acl.lesson.roles')->replaceArgument($index, $config['acl_roles']['lesson'][$perm]);
            $container->getDefinition('n1c0_lesson.acl.introduction.roles')->replaceArgument($index, $config['acl_roles']['introduction'][$perm]);
            $container->getDefinition('n1c0_lesson.acl.chapter.roles')->replaceArgument($index, $config['acl_roles']['chapter'][$perm]);
            $container->getDefinition('n1c0_lesson.acl.argument.roles')->replaceArgument($index, $config['acl_roles']['argument'][$perm]);
            $container->getDefinition('n1c0_lesson.acl.conclusion.roles')->replaceArgument($index, $config['acl_roles']['conclusion'][$perm]);
        }

        $container->setAlias('n1c0_lesson.acl.lesson', $config['service']['acl']['lesson']);
        $container->setAlias('n1c0_lesson.acl.introduction', $config['service']['acl']['introduction']);
        $container->setAlias('n1c0_lesson.acl.chapter', $config['service']['acl']['chapter']);
        $container->setAlias('n1c0_lesson.acl.argument', $config['service']['acl']['argument']);
        $container->setAlias('n1c0_lesson.acl.conclusion', $config['service']['acl']['conclusion']);
    }
}
