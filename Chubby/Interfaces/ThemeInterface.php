<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby\Interfaces;

interface ThemeInterface 
{
	/**
	 * Construct the theme object. 
	 *
	 * @param string $name The default name. Chubby expects this to be 'Default'.
	 */
	public function __construct( $name = 'Default' );
	
	/**
	 * Return the theme name.
	 */
	public function __toString();
	
	/**
	 * Return the theme name.
	 */
	public function getName();
} // interface 

// EOF 