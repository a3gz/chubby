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
     */
    public static function load()
    {
        $path = APP_PATH . DS . 'Modules';
		
        if ( !is_dir($path) ) 
		{
			throw new \Exception( "Could not find the modules where expected: {$path}" );
		}
		
		$modules = [];
		
        foreach (new \DirectoryIterator($path) as $fileInfo)
        {
            if ($fileInfo->isDot() || $fileInfo->isFile()) continue;

			$className = "{$fileInfo->getBasename()}Module";

			$fullClassName = \Chubby\AppFactory::getApp()->appNamespace() . "\\Modules\\{$fileInfo->getBasename()}\\{$className}";
			
			$moduleObject = new $fullClassName();
			
			$required = 'Chubby\ChubbyModule';
			if ( !($moduleObject instanceof \Chubby\ChubbyModule ) )
			{
				throw new \Exception( "Module class {$fullClassName} MUST extend {$required}." );
			}
			
			$module = new $fullClassName();
			$priority = $module->getPriority();
			if ($priority < 0) $priority = 0; // Highest allowed priority
			
			$modules[$priority][$fullClassName] = $module;
        }
		
		/**
		 * Modules are sorted by priority to allow for manual initialization sequence.
		 */
		ksort($modules);
		
		return $modules;
    } // load()
    
} // class 

// EOF 
