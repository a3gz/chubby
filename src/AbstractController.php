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
     * See: $this->getService()
     */
    public function __get( $key )
    {
        return $this->getService($key);
    } // __get()


    /**
     * See: $this->hasService()
     */
    public function __isset( $key )
    {
        return $this->hasService( $key );
    } // __isset()
    
    
    /**
     *
     */
    protected function findView( $subPath )
    {
        $themeName = 'default';
        
        $fullPath = APP_PATH . DS . 'Modules' . DS . $this->getModuleName() . DS . 'Views' . DS . $themeName . DS . $subPath . '.php';
        
    } // findView()    
    
    
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
     * Getter that functions as a proxy to the DI container.
     * This provides familiarity to Slim programers by allowing them to access the container 
     * as $this->varname
     *
     * @param string $key Attribute or service name 
     *
     * @return mixed The corresponding attribute in the application's DI container
     */
    public function getService( $key )
    {
        return $this->container->get($key);
    } // getService()
    
    
    /**
     * @return \Slim\App The instance of Slim stored in the singleton Chubby\App object.
     */
    public function getSlim()
    {
        return \Chubby\AppFactory::getApp()->getSlim();
    } // getSlim()
    
    
    /**
     * @param string $key Attribute or service name 
     *
     * @return bool 
     */
    public function hasService( $key )
    {
        return $this->container->has($key);
    } // hasService()    
} // class 

// EOF