<?php

/**
 * This file is part of the "cosma/testing-bundle" project
 *
 * (c) Cosmin Voicu<cosmin.voicu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 11/07/14
 * Time: 23:33
 */

namespace Cosma\Bundle\TestingBundle\DependencyInjection;

use Cosma\Bundle\TestingBundle\ORM\DoctrineORMSchemaTool;
use Cosma\Bundle\TestingBundle\ORM\SchemaTool;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CosmaTestingExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container = $this->setDoctrineCleaningStrategy($container, $config);

        if (isset($config['fixture_directory'])) {
            $container->setParameter('cosma_testing.fixture_directory', $config['fixture_directory']);
        }

        if (isset($config['tests_directory'])) {
            $container->setParameter('cosma_testing.tests_directory', $config['tests_directory']);
        }

        if (isset($config['solarium']['host'])) {
            $container->setParameter('cosma_testing.solarium.host', $config['solarium']['host']);
        }

        if (isset($config['solarium']['port'])) {
            $container->setParameter('cosma_testing.solarium.port', $config['solarium']['port']);
        }

        if (isset($config['solarium']['path'])) {
            $container->setParameter('cosma_testing.solarium.path', $config['solarium']['path']);
        }

        if (isset($config['solarium']['core'])) {
            $container->setParameter('cosma_testing.solarium.core', $config['solarium']['core']);
        }

        if (isset($config['solarium']['timeout'])) {
            $container->setParameter('cosma_testing.solarium.timeout', $config['solarium']['timeout']);
        }

        if (isset($config['elastica']['host'])) {
            $container->setParameter('cosma_testing.elastica.host', $config['elastica']['host']);
        }

        if (isset($config['elastica']['port'])) {
            $container->setParameter('cosma_testing.elastica.port', $config['elastica']['port']);
        }

        if (isset($config['elastica']['path'])) {
            $container->setParameter('cosma_testing.elastica.path', $config['elastica']['path']);
        }

        if (isset($config['elastica']['timeout'])) {
            $container->setParameter('cosma_testing.elastica.timeout', $config['elastica']['timeout']);
        }

        if (isset($config['elastica']['index'])) {
            $container->setParameter('cosma_testing.elastica.index', $config['elastica']['index']);
        }

        if (isset($config['selenium']['remote_server_url'])) {
            $container->setParameter('cosma_testing.selenium.remote_server_url', $config['selenium']['remote_server_url']);
        }

        if (isset($config['selenium']['test_domain'])) {
            $container->setParameter('cosma_testing.selenium.test_domain', $config['selenium']['test_domain']);
        }
    }

    public function getAlias()
    {
        return 'cosma_testing';
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @return ContainerBuilder
     */
    private function setDoctrineCleaningStrategy(ContainerBuilder $container, $config)
    {
        if (isset($config['doctrine']['cleaning_strategy'])) {
            $doctrineCleaningStrategy = $config['doctrine']['cleaning_strategy'];
        } else {
            $doctrineCleaningStrategy = DoctrineORMSchemaTool::DOCTRINE_CLEANING_TRUNCATE;
        }

        $container->setParameter('cosma_testing.doctrine.cleaning_strategy', $doctrineCleaningStrategy);

        if (DoctrineORMSchemaTool::DOCTRINE_CLEANING_TRUNCATE == $doctrineCleaningStrategy) {
            $container->setParameter(
                'h4cc_alice_fixtures.orm.schema_tool.doctrine.class',
                'Cosma\Bundle\TestingBundle\ORM\DoctrineORMSchemaTool'
            );
        }

        return $container;
    }
}
