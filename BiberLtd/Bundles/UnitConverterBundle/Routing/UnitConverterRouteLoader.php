<?php
/**
 * CurrencyRouteLoader
 *
 * Route loader for UnitConverterBundle
 *
 * @vendor      BiberLtd
 * @package		UnitConverterBundle
 * @subpackage	Routing
 * @name	    CurrencyRouteLoader
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        30.06.2013
 *
 */
namespace BiberLtd\Bundles\UnitConverterBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Parser;


class UnitConverterRouteLoader implements LoaderInterface{
    private $loaded = false;

    public function load($resource, $type = null){
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $route_container = new RouteCollection();
        $yaml = new Parser();
        $routes = $yaml->parse(file_get_contents($resource));

        foreach($routes as $name => $config){
            $defaults = array();
            $requirements = array();
            $pattern = $config['pattern'];
            if(isset($config['defaults'])){
                foreach($config['defaults'] as $key => $value){
                    $defaults[$key] = $value;
                }
            }
            if(isset($config['requirements'])){
                foreach($config['requirements'] as $key => $value){
                    $requirements[$key] = $value;
                }
            }
            $route = new Route($pattern, $defaults, $requirements);
            $route_container->add($name, $route);
        }

        //return $route_container;
    }

    public function supports($resource, $type = null){
        return 'yaml' === $type;
    }

    public function getResolver(){
    }

    public function setResolver(LoaderResolverInterface $resolver){

    }
}