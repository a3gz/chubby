<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

interface AppInterface 
{
	/**
	 * Returns the list of registered modules
	 *
	 * @param bool $priorities Modifier used to request the modules discriminated by priority or all together as a plain list.
	 *
	 * @return array List of registered modules.
	 */
	public function getModules( $priorities );
    
    
    /**
     * Returns the instance of the Slimn application
     */
    public function getSlim();
    
    
    /**
     *
     */
    public function isDebug();

    
    /**
     * This is where things get in motion. 
     * Every Chubby application is composed of one or more modules. Chubby expects that at least one module exists, otherwise it will 
     * throw an error.
     */
    public function run( $appNamespace );
} // interface 

// EOF 