<?php
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
 
/**
 * The home path is where the index.php file is located.
 */
define( 'HOME_PATH', __DIR__ ); 

/**
 * Directory separator.
 * Used as a shorter version for PHP's DIRECTORY_SEPARATOR constant. 
 */
defined( 'DS' ) || define( 'DS', DIRECTORY_SEPARATOR );

/**
 * Search for local settings. 
 */
if ( is_readable( HOME_PATH . DS . 'debug.php' ) ) 
{
	include HOME_PATH . DS . 'debug.php'; // Dev environment only 
}
defined( 'DEBUG' ) || define( 'DEBUG', false );

/**
 * Chubby is conceived to organize code in a way that every single file is located outside the public_html directory.
 * The PRIVATE_HTML constant should point to the root directory to your projects. 
 */
defined( 'PRIVATE_HTML' ) || define( 'PRIVATE_HTML', DS . 'home' . DS . 'user' . DS . 'private_html' );

/**
 * Location of this application's files. 
 * Here we are assuming that the application is located under a directory named the same as \
 * the directory containing the index.php file. 
 */ 
defined( 'APP_PATH' ) || define( 'APP_PATH', PRIVATE_HTML . DS . basename( __DIR__ ) );

/**
 * Create the application object and run...
 */
require APP_PATH . DS . 'vendor' . DS . 'autoload.php';
\Chubby\AppFactory::getApp()->run(); 
