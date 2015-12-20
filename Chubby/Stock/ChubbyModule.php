<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby\Stock;

abstract class ChubbyModule
{
	const MAIN_MODULE_NAME = 'MainModule';
	
	/**
	 * $app 
	 * @var \Chubby\ChubbyApp
	 */
	protected $app = null;
	
	
	/**
	 * $priority 
	 * @var integer The module's priority used as a sort index before initialization.
	 */
	protected $priority = 100; 
	

	/**
	 *
	 */
	private function getFullyQualifiedCallable( $callable )
	{
		$r = new \ReflectionClass( $this );
		
		/**
		 * First we find out the path to the module from which this is being invoked.
		 * Then, we complete the path assuming that the callable is a controller located 
		 * under ModuleName\Controllers\
		 */
		$className = dirname( str_replace( '\\', DIRECTORY_SEPARATOR, $r->getName() ) );
		$className = str_replace( DIRECTORY_SEPARATOR, '\\', $className );
		
		$path = "{$className}\\Controllers\\{$callable}";
		
		return $path;
	} // getFullyQualifiedCallable()
	
	
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
		
		if ( $this->isMain() )
		{
			$priority = 0;
		}
		
		return $priority;
	} // getPriority()
	
	
	/**
	 *
	 */
	final public function isMain()
	{
		return ( $this->getName() == self::MAIN_MODULE_NAME );
	} // isMain()

	
	/**
	 *
	 */
	public function setApp( \Chubby\ChubbyApp $app )
	{
		$this->app = $app;
		return $this;
	} // setApp()
	
	
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
		$callable = $this->getFullyQualifiedCallable( $callable );
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
		$callable = $this->getFullyQualifiedCallable( $callable );
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
		$callable = $this->getFullyQualifiedCallable( $callable );
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
		$callable = $this->getFullyQualifiedCallable( $callable );
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
		$callable = $this->getFullyQualifiedCallable( $callable );
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
		$callable = $this->getFullyQualifiedCallable( $callable );
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
		$callable = $this->getFullyQualifiedCallable( $callable );
		return $this->app->getSlim()->any($pattern, $callable);
    }
	 
	
} // class 

// EOF