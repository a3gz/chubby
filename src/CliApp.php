<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

final class CliApp implements AppInterface
{
    const ROOT_NAMESPACE = 'ChubbyApp';
	const WITH_PRIORITIES = true;
	const IGNORE_PRIORITIES = false;
    
    /**
     * @var array 
     */
    protected $argv;
    
    
    /**
     * @var string
     */
    protected $method;
    
     
     
	/**
	 * Returns the list of registered modules
	 *
	 * @param bool $priorities Modifier used to request the modules discriminated by priority or all together as a plain list.
	 *
	 * @return array List of registered modules.
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
     * Setup environment variables in a way taht Slim can process them.
     *
     * @return \Slim\Http\Environment
     */
    protected function getEnvironmentSettings()
    {
        $r = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => $this->method,
            'REQUEST_URI' => $this->getRequestUri()
        ]);
        return $r;
    } // getEnvironmentSettings()
    
    
    /**
     * CLI call arguments are stored locally as an array. 
     * For this information to be recognized by Slim as a REQUEST_URI string, we will 
     * assume that each argument in the command line is a URL component as in the following example:
     *      php index.php users 123 
     * will become
     *      /users/123 
     *
     * @return string
     */
    public function getRequestUri()
    {
        $r = implode('/', $this->argv);
        
        // Make sure that the request uri string begins with a slash (/). 
        if ( substr($r, 0, 1) != '/' ) {
            $r = "/{$r}";
        }
        
        return $r;
    } // getRequestUri()
    

    /**
     * Returns the instance of the Slimn application
     */
    public function getSlim()
    {
        if ( !$this->isValidSlimApp( $this->slim ) ) {
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
     * Determines if $p is an instance of a Slim application object
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
        
        //
        // Pass environment settings to Slim so the CLI request is properly recognized
        $container['environment'] = $this->getEnvironmentSettings();
    
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
    
    
    /**
     * Returns the application object having the command line arguments.
     *
     * @param array $argv 
     *
     * @return $this
     */
    public function withArgv( $argv )
    {
        $clone = clone $this;
        
        // remove the script file name from the arguments.
        array_shift( $argv ); 
        
        // The first argument MUST ALWASY BE the method: 
        $method = strtoupper( array_shift( $argv ) );
        
        $clone->method = $method; 
        $clone->argv = $argv;
        return $clone;
    } // withArgv()
} // class 

// EOF