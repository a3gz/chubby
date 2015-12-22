<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

class Theme
{
    /**
     * $name 
     *
     * @var string The theme name. 
     */
    protected $name = 'Default';
     
     
    /**
     *
     */
    public function __construct( $name = 'Default' )
    {
        $this->name = $name;
    } // __construct()
    
    
    /**
     *
     */
    public function getName()
    {
        return $this->name;
    } // getName()
} // class 

// EOF 
