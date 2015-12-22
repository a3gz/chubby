<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

abstract class Controller
{
    /**
     * Returns the name of the module in which the controller is located.
     *
     * @return string The module name.
     */
    protected function getModuleName()
    {
        $r = new \ReflectionClass( $this );
        $className = $r->getName();
        
        $parts = [];
        preg_match( "^(.*)\\Modules\\([^\\]+)\\(.*)$", $className, $parts );
        
        return $parts[2];
    } // getModuleName()
    
    
    /**
     *
     */
    protected function findView( $subPath )
    {
        $themeName = 'default';
        
        $fullPath = APP_PATH . DS . 'Modules' . DS . $this->getModuleName() . DS . 'Views' . DS . $themeName . DS . $subPath . '.php';
        
    } // findView()
} // class 

// EOF