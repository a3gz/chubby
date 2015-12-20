<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

final class ChubbyApp
{
	const DEFAULT_NAMESPACE = 'MyChubbyApp';
	
	
	/**
	 * $appNamespace 
	 * @var string
	 */
	public function appNamespace() {
		return $this->appNamespace;
	} protected $appNamespace; 

	/**
	 * Instance of Composer's autoloader
	 * @var \ComposerAutoloaderX
	 */
	public $loader;
	
	/**
	 * $modules
	 * @var array
	 */
	protected $modules;
	
	/**
	 * $slim 
	 * @var  \Slim\App
	 */
	protected $slim = null;
	
	
	/**
	 *
	 */
	public function getSlim()
	{
		if ( !$this->isValidSlimApp( $this->slim ) )
		{
			throw new \Exception( "The Slim application has not been properly created. Check your MainModule::newSlim()." );
		}
		return $this->slim;
	} // getSlim()
	
	
	/**
	 *
	 */
	public function isDebug()
	{
		return ( defined('DEBUG') && (DEBUG === true) );
	} // isDebug()
	
	
	/**
	 *
	 */
	private function isValidSlimApp( $p )
	{
		return ( isset($p) && ($p instanceof \Slim\App) );
	} // isValidSlimApp()
	
	
	/**
	 * This is where things get in motion. 
	 * Every Chubby application is composed of one or more modules. Chubby expects that at least one module exists, otherwise it will 
	 * throw an error.
	 */
	public function run( $appNamespace = self::DEFAULT_NAMESPACE )
	{
		/**
		 * Each application must exist inside its own namespace. Chubby uses that namespace to search for modules. 
		 */
		$this->appNamespace = $appNamespace;
		
		$this->modules = \Chubby\ModuleLoader::load();
		
		if ( !is_array($this->modules) || !count($this->modules) )
		{
			throw new \Exception( "Chubby Framework requires at least one module." );
		}
		
		/**
		 * Initialize the modules following the order given by each module's priority.
		 */
		foreach( $this->modules as $priority => $modules )
		{
			foreach( $modules as $module )
			{
				$module->setApp( $this );
				
				/**
				 * The MainModule must create the Slim\App instance. 
				 * Why not create it here? Because the programmer should be able to pass in containers or settings as 
				 * they would do in a plain Slim application.
				 */
				if ( $module->isMain() )
				{
					if ( !method_exists( $module, 'newSlim' ) )
					{
						throw new \Exception( "{$module->getName()} MUST implement the newSlim() method and return an instance of Slim\App." );
					}
					
					$slim = $module->newSlim();
					if ( !$this->isValidSlimApp( $slim ) )
					{
						throw new \Exception( "MainModule::newSlim() MUST return an instance of Slim\App" );
					}
					
					$this->slim = $slim;
				}
				
				$module->init();
			}
		}
		
		$this->slim->run();
		
		return $this;
	} // run()
	
} // class 

// EOF