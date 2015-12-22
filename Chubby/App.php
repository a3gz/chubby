<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

final class App
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
     * Returns the instance of the Slimn application
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
    public function run( $appNamespace = self::DEFAULT_NAMESPACE )
    {
        /**
         * Each application must exist inside its own namespace. Chubby uses that namespace to search for modules. 
         */
        $this->appNamespace = $appNamespace;

        
        $container = new \Slim\Container();
        
        $this->modules = \Chubby\ModuleLoader::load( $container );
        
        if ( !is_array($this->modules) || !count($this->modules) )
        {
            throw new \Exception( "Chubby Framework requires at least one module." );
        }

        /**
         * After loading all the modules, the container has everything (if anything) the modules 
         * wanted to inject, so we can create the slim instance now
         */
        $this->slim = new \Slim\App( $container );

        
        /**
         * Initialize the modules following the order given by each module's priority.
         */
        foreach( $this->modules as $priority => $modules )
        {
            foreach( $modules as $module )
            {
                $module->setApp( $this );

                $module->init();
            }
        }

        $this->slim->run();

        return $this;
    } // run()

} // class 

// EOF