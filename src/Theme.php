<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

class Theme implements Interfaces\ThemeInterface
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
	 * When treated as a string, the Theme object returns the theme's name.
	 *
	 * @return string The theme's name.
	 */
	public function __toString()
	{
		return $this->name;
	} // __toString()
    
    
    /**
     *
     */
    public function getName()
    {
        return $this->name;
    } // getName()
	
	
	/**
	 *
	 */
	public function setName( $name )
	{
		$this->name = $name;
	} // setName()
} // class 

// EOF 
