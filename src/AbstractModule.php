<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

abstract class AbstractModule
{
    const MAIN_MODULE_NAME = 'MainModule';

    /**
     * $app 
     * @var \Chubby\App
     */
    protected $app = null;
    

    /**
     * $priority 
     * @var integer The module's priority used as a sort index before initialization.
     */
    protected $priority = 100; 
	

    /**
     * Returns the module's class name without the namespace path.
     */
    public function getName()
    {
        $self = new \ReflectionClass( $this );
        $path = str_replace( '\\', DIRECTORY_SEPARATOR, $self->getName() );
        return basename( $path );
    } // getName();


    /**
     * Returns the module's priority. 
     * A special case is the MainModule, which will always have a priority 0 and it SHOULD be the *
     * only one with such priority.
     *
     * @return integer The priority
     */
    final public function getPriority()
    {
        $priority = $this->priority;

        if ( $this->isMain() ) {
			$priority = 0;
        }

        return $priority;
    } // getPriority()

    
    /**
     * Module initialization. It must be implemented by sub-modules.
     */
    public abstract function init();
    

    /**
     * Determines if the current module is the required main module
     */
    final public function isMain()
    {
        return ( $this->getName() == self::MAIN_MODULE_NAME );
    } // isMain()


    /**
     *
     */
    protected function resolveCallable( $callable )
    {
        $r = new \ReflectionClass( $this );

        /**
         * First we find out the path to the module from which this is being invoked.
         * Then, we complete the path assuming that the callable is a controller located 
         * under ModuleName\Controllers\
         */
        $className = dirname( str_replace( '\\', DIRECTORY_SEPARATOR, $r->getName() ) );
        $className = str_replace( DIRECTORY_SEPARATOR, '\\', $className );

        $path = "{$className}\\{$callable}";

        return $path;
    } // resolveCallable()


    /**
     * Makes the chubby application available to the module
     */
    public function setApp( \Chubby\App $app )
    {
        $this->app = $app;
        return $this;
    } // setApp()
    
    
    /**
     * Placeholder method.
     * Modules that want to inject dependency to the Slim application can use this method to
     * modify the container that will utlimately be passed to Slim upon construction.
     *
     * @param \Interop\Container\ContainerInterface $container 
     */
    public function onLoad( \Interop\Container\ContainerInterface $container )
    {
        // placeholder
        return $this;
    } // onLoad()
    


    /********************************************************************************
     * Router proxy methods
     *
     * These methods are based on those from Slim\App.
     *
     * Chubby proposes a way of organizing the code that don't favor the use of 
     * closures; it favors instead separated controllers. In this line Slim's 
     * alternative method for registering handlers is used: "Namespace\To\Controller:method". 
     * The problem we face here is that Slim needs the fully qualified namespae to the 
     * class or it won't be able to find it. This would imply to always spacify pahts 
     * such as: \MyChubbyApp\Modules\Module\Main\Controllers\MainController:get
     * However we want to allow for the much simpler way: MainController:get.
     *
     * Chubby has the know how to determine the module from whihc the route is being 
     * registered, so it can take the simple form and translate it to the fully 
     * qualified version; and that is the whole purpose of these wrappers for Slim's
     * proxy methods. 
     *******************************************************************************/

    /**
     * Add GET route
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function get($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->get($pattern, $callable);
    }
    

    /**
     * Add POST route
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function post($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->post($pattern, $callable);
    }

    
    /**
     * Add PUT route
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function put($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->put($pattern, $callable);
    }

    
    /**
     * Add PATCH route
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function patch($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->patch($pattern, $callable);
    }

    
    /**
     * Add DELETE route
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function delete($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->delete($pattern, $callable);
    }

    
    /**
     * Add OPTIONS route
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function options($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->options($pattern, $callable);
    }

    
    /**
     * Add route for any HTTP method
     *
     * @param  string $pattern  The route URI pattern
     * @param  mixed  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function any($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->any($pattern, $callable);
    }


    /**
     * Route Groups
     *
     * This method accepts a route pattern and a callback. All route
     * declarations in the callback will be prepended by the group(s)
     * that it is in.
     *
     * @param string   $pattern
     * @param callable $callable
     *
     * @return RouteGroupInterface
     */
    public function group($pattern, $callable)
    {
        $callable = $this->resolveCallable( $callable );
        return $this->app->getSlim()->group($pattern, $callable);
    }

} // class 

// EOF