<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

final class App implements AppInterface
{
    const ROOT_NAMESPACE = 'ChubbyApp';
	const WITH_PRIORITIES = true;
	const IGNORE_PRIORITIES = false;


    /**
     * @var string
     */
    public $appNamespace; 

    /**
     * Instance of Composer's autoloader
     * @var \ComposerAutoloaderX
     */
    public $loader;

    /**
     * @var array
     */
    protected $modules;
    
    /**
     * @var  \Slim\App
     */
    protected $slim = null;

	
	
	/**
	 * @inheritdoc
	 */
	public function getModules( $priorities = self::IGNORE_PRIORITIES )
	{
		$modules = $this->modules;
		
		if ( $priorities == self::IGNORE_PRIORITIES ) { 
			$modules = [];
			foreach ( $this->modules as $priority => $priorityModules ) {
				foreach( $priorityModules as $name => $module ) {
					$modules[$name] = $module;
				}
			}
		}

		return $modules;
	} // getModules()
	
    
    /**
     *
     */
    private function getContainerConfig() 
    {
        $container = [];
        
        $configFilePath = APP_PATH . DIRECTORY_SEPARATOR;
        $configFileName =  'container.config.php';
        
        // Allow for an alternative container configuration file when in DEBUG mode.
        if ( $this->isDebug() ) {
            $debugConfigFileName = "debug.{$configFileName}";
            if ( is_readable( "{$configFilePath}{$debugConfigFileName}" ) ) {
                $configFileName = $debugConfigFileName;
            }
        }
        
        $configFileName = "{$configFilePath}{$configFileName}";
        if (is_readable($configFileName)) {
            $container = new \Slim\Container( include $configFileName );
        }
        
        return $container;
    } // getContainerConfig()
    

    /**
	 * @inheritdoc
     */
    public function getSlim()
    {
        if ( !$this->isValidSlimApp( $this->slim ) ) {
            throw new \Exception( "The Slim application has not been properly created. Check your MainModule::newSlim()." );
        }
        return $this->slim;
    } // getSlim()


    /**
	 * @inheritdoc
     */
    public function isDebug()
    {
        return ( defined('DEBUG') && (DEBUG === true) );
    } // isDebug()


    /**
     * Determines if $p is an instance of a Slim application object
     */
    private function isValidSlimApp( $p )
    {
        return ( isset($p) && ($p instanceof \Slim\App) );
    } // isValidSlimApp()


    /**
	 * @inheritdoc
     */
    public function run( $appNamespace = self::ROOT_NAMESPACE )
    {
        //
        // Each application must exist inside its own namespace. Chubby uses that namespace to search for modules. 
        $this->appNamespace = $appNamespace;
        
        //
        // Slim can be initiated in one of two ways:
        // 1. Without a container. Slim will create the default container. 
        // 2. Receiving a container in the constructor. We can pass Slim some settings and services 
        //      by passing a pre-created container. We do this here via a configuration file. 
        $container = $this->getContainerConfig();
        $this->slim = new \Slim\App( $container );
        $container = $this->slim->getContainer();
        
        //
        $this->modules = \Chubby\PackageLoader::loadModules( $container );
        
        if ( !is_array($this->modules) || !count($this->modules) ) {
            throw new \Exception( "Chubby Framework requires at least one module." );
        }
       
        //
        // Initialize the modules following the order given by each module's priority.
        foreach( $this->modules as $priority => $modules ) {
            foreach( $modules as $module ) {
                
                $module['object']->setApp( $this );

                $module['object']->init();
            }
        }

        //
        $this->slim->run();

        return $this;
    } // run()
} // class 

// EOF