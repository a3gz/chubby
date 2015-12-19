<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby\Stock;

abstract class ChubbyModule
{
	const MAIN_MODULE_NAME = 'MainModule';
	
	/**
	 * $app 
	 * @var \Chubby\ChubbyApp
	 */
	protected $app = null;
	
	
	/**
	 * $priority 
	 * @var integer The module's priority used as a sort index before initialization.
	 */
	protected $priority = 100; 
	

	/**
	 * Returns the module's class name without the namespace path.
	 */
	public function getName()
	{
		$self = new \ReflectionClass( $this );
		$path = str_replace( '\\', DIRECTORY_SEPARATOR, $self->getName() );
		return basename( $path );
	} // getName();
	
	
	/**
	 * Returns the module's priority. 
	 * A special case is the MainModule, which will always have a priority 0 and it SHOULD be the *
	 * only one with such priority.
	 *
	 * @return integer The priority
	 */
	final public function getPriority()
	{
		$priority = $this->priority;
		
		if ( $this->isMain() )
		{
			$priority = 0;
		}
		
		return $priority;
	} // getPriority()
	
	
	/**
	 *
	 */
	final public function isMain()
	{
		return ( $this->getName() == self::MAIN_MODULE_NAME );
	} // isMain()

	
	/**
	 *
	 */
	public function setApp( \Chubby\ChubbyApp $app )
	{
		$this->app = $app;
		return $this;
	} // setApp()
	
} // class 

// EOF