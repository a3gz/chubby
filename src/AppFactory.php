<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

final class AppFactory
{
    /**
     * Returns an application object specialized for the type of incoming request. 
     * Chubby is capable of responding to HTTP requests as well as command line requests via the same
     * index.php file. 
     */
    public static function getApp()
    {
        //
        // Application singleton
        //
        static $app = null; 

        //
        // Build the application if it hasn't been built yet
        //
        if ( $app == null ) {
            global $argv;

            if ( ( PHP_SAPI == 'cli' ) && isset($argv) && count($argv) && (basename($argv[0]) == 'index.php') ) { 
                // This is a command line request             
                $app = (new \Chubby\CliApp())->withArgv( $argv );
            } else  {
                // This is an HTTP request                
                $app = new \Chubby\App();
            }
        }

        return $app;
    } // getApp()

} // class 

// EOF