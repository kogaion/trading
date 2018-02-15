<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/11/2018
 * Time: 12:46 PM
 */

namespace AppBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Config'));
    
        $loader->load('controllers.yml');
        $loader->load('services.yml');
    
    }
}