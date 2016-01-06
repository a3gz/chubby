<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

final class PackageLoader
{
	/**
	 * Scans composer's autoload_psr4 registry in search for Chubby packages of the required type. 
     * 
	 * @return array A list of package locations
	 */
	public static function findPackages( $type )
	{
        $validTypes = [ 'Modules' ];
        
		$sources = [];
        
        if ( in_array($type, $validTypes) ) {
            $map = require VENDOR_PATH . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'autoload_psr4.php';

            foreach( $map as $namespace => $path ) {
                $path = array_shift($path);
                $mark = "Chubby\\{$type}\\";
                if ( strpos( $namespace, $mark ) === 0 ) {
                    // Remove ending back-slash 
                    if ( substr( $namespace, -1 ) == '\\' ) {
                        $namespace = substr( $namespace, 0, -1 );
                    }
                    // Register the source 
                    $sources[] = [
                        'namespace' => $namespace,
                        'path' => str_replace( '/', DIRECTORY_SEPARATOR, $path )
                    ];
                }
            }
        }
        
		return $sources;
	} // findPackages()

    
    /**
     * Loads modules from all recognizable locations. 
	 * To find moudles Chubby uses composer's map stored at APP_PATH/vendor/composer/autoload_psr4.php
	 * Chubby expects two locations for modules: the application's APP_PATH/Modules and as composer packages
	 * installed somewhere under APP_PATH/vendor. 
	 * Installed modules are required to have the root namespace Chubby\Modues so they can be found by this loader. 
     */
    public static function loadModules( \Slim\Container $container )
    {
		$sources = array_merge([[
				'path' => APP_PATH . DS . 'Modules'
			]], 
			self::findPackages( 'Modules' ) 
		);

        $modules = [];

		foreach( $sources as $source ) {
			$path = $source['path'];

			foreach (new \DirectoryIterator($path) as $fileInfo) {
				if ($fileInfo->isDot() || $fileInfo->isFile()) {
                    continue;
                }

                $moduleName = $fileInfo->getBasename();
                $className = "{$moduleName}Module";

				if ( isset($source['namespace']) ) {
					$modulesNameSpace = "\\{$source['namespace']}";
				} else {
					$modulesNameSpace = \Chubby\AppFactory::getApp()->appNamespace . "\\Modules";
				}
                
                $moduleClassName = "{$modulesNameSpace}\\{$moduleName}\\{$className}";

                //
                // Load the module class
				$moduleObject = new $moduleClassName();

				$required = 'Chubby\AbstractModule';
				if ( !($moduleObject instanceof \Chubby\AbstractModule ) ) {
					throw new \Exception( "Module class {$moduleClassName} MUST extend {$required}." );
				}

				$module = new $moduleClassName();
				$priority = $module->getPriority();
				if ($priority < 0) $priority = 0; // Highest allowed priority
				
				// Only MainModule is allowed to have prioarity 0
				if ( ( $priority == 0 ) && ($className != 'MainModule') ) {
					$priority = 1;
				}

				$modules[$priority][$moduleName] = [
					'object' => $module,
					'path' => $path
				];
			}
		}
		
        //
        // Modules are sorted by priority to allow for manual initialization sequence.
        ksort($modules);

        return $modules;
    } // loadModules()
    
} // class 

// EOF 
