<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

final class ModuleLoader
{
	/**
	 *
	 * @return array A list of module locations
	 */
	private static function findPackageModules()
	{
		$map = require APP_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'autoload_psr4.php';

		$sources = [];
		foreach( $map as $namespace => $path )
		{
			$path = array_shift($path);
			$mark = "Chubby\\Modules\\";
			if ( strpos( $namespace, $mark ) === 0 )
			{
				$namespace = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace );
				$namespace = str_replace( DIRECTORY_SEPARATOR, '\\', dirname($namespace) );
				$sources[] = [
					'namespace' => $namespace,
					'path' => str_replace( '/', DIRECTORY_SEPARATOR, dirname($path) )
				];
			}
		}

		return $sources;
	} // findPackageModules()
	
    
    /**
     * Loads modules from all recognizable locations. 
	 * To find moudles Chubby uses composer's map stored at APP_PATH/vendor/composer/autoload_psr4.php
	 * Chubby expects two locations for modules: the application's APP_PATH/Modules and as composer packages
	 * installed somewhere under APP_PATH/vendor. 
	 * Installed modules are required to have the root namespace Chubby\Modues so they can be found by this loader. 
     */
    public static function load( \Interop\Container\ContainerInterface $container )
    {
		$sources = array_merge([[
				'path' => APP_PATH . DS . 'Modules'
			]], 
			self::findPackageModules() 
		);

        $modules = [];

		foreach( $sources as $source )
		{
			$path = $source['path'];

			foreach (new \DirectoryIterator($path) as $fileInfo)
			{
				if ($fileInfo->isDot() || $fileInfo->isFile()) continue;

                $moduleName = $fileInfo->getBasename();
                $className = "{$moduleName}Module";

				if ( isset($source['namespace']) )
				{
					$fullClassName = "\\{$source['namespace']}";
				}
				else 
				{
					$fullClassName = \Chubby\AppFactory::getApp()->appNamespace . "\\Modules";
				}
                
                $fullClassName .= "\\{$moduleName}\\{$className}";

				$moduleObject = new $fullClassName();

				$required = 'Chubby\Module';
				if ( !($moduleObject instanceof \Chubby\Module ) )
				{
					throw new \Exception( "Module class {$fullClassName} MUST extend {$required}." );
				}

				$module = new $fullClassName();
				$priority = $module->getPriority();
				if ($priority < 0) $priority = 0; // Highest allowed priority
				
				// Only MainModule is allowed to have prioarity 0
				if ( ( $priority == 0 ) && ($className != 'MainModule') )
				{
					$priority = 1;
				}
				
				$module->onLoad( $container );

				$modules[$priority][$moduleName] = [
					'object' => $module,
					'path' => $path
				];
			}
		}
		
        /**
        * Modules are sorted by priority to allow for manual initialization sequence.
        */
        ksort($modules);

        return $modules;
    } // load()
    
} // class 

// EOF 
