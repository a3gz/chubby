<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

abstract class AbstractController
{
    /**
     * $container
     *
     * @var \Interop\Container\ContainerInterface
     */
    public $container; 
    
    
    /**
     *
     */
    public function __construct( \Interop\Container\ContainerInterface $container = null )
    {
        $this->container = $container;
    } // __construct()
    
    
    /**
     * Getter that functions as a proxy to the DI container.
     * This provides familiarity to Slim programers by allowing them to access the container 
     * as $this->varname
     *
     * @param string $key Attribute or service name 
     *
     * @return mixed The corresponding attribute in the application's DI container
     */
    public function __get( $key )
    {
        return $this->container[$key];
    } // __get()
    
    
    /**
     * Setter that functions as a proxy to the DI container
     *
     * @param string $key Variable name
     * @param mixed $val Value 
     */
    public function __set( $key, $val )
    {
        $this->container[$key] = $val;
    } // __set()
    
    
    /**
     * Returns the name of the module in which the controller is located.
     *
     * @return string The module name.
     */
    protected function getModuleName()
    {
        $r = new \ReflectionClass( $this );
        $className = $r->getName();
        
        $parts = [];
        preg_match( "^(.*)\\Modules\\([^\\]+)\\(.*)$", $className, $parts );
        
        return $parts[2];
    } // getModuleName()
    
    
    /**
     * @return \Slim\App The instance of Slim stored in the singleton Chubby\App object.
     */
    public function getSlim()
    {
        return \Chubby\AppFactory::getApp()->getSlim();
    } // getSlim()
    
    
    /**
     *
     */
    protected function findView( $subPath )
    {
        $themeName = 'default';
        
        $fullPath = APP_PATH . DS . 'Modules' . DS . $this->getModuleName() . DS . 'Views' . DS . $themeName . DS . $subPath . '.php';
        
    } // findView()
} // class 

// EOF