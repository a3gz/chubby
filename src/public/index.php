<?php
/** 
 * ATTENTION:   You shouldn't have to modify this file. Use ./bootstrap.php instead.
 *              If the only way to achieve what you need is by modifying this file, please
 *              contact me ( alejandro@roetal.com ) and tell me about your ideas.
 *
 * It is assumed that the application's tree structure is as follows: 
 * /path-to/public_html/app-name
 *                         +-- src
 *                              +-- app
 *                              |    +-- config
 *                              +-- public 
 *                              +-- vendor
 *
 * Chubby allows to have the content of app-name/src inside a directory outside
 * public_html, which would result in the following structure: 
 *
 * /path-to/public_html/app-name
 *                         +-- src
 *                              +-- public 
 *
 * /path-to/private_html/app-name
 *                          +-- src
 *                               +-- app 
 *                               |    +-- config
 *                               +-- vendor
 */

/**
 * Use the local.php file to define your own paths to make things work in your specific environment. 
 */
if ( is_readable( 'local.php' ) ) {
    include 'local.php';
}

// Path to your application's public files. Normally this is somewhere below .../public_html
defined( 'PUBLIC_APP_PATH' )    || define( 'PUBLIC_APP_PATH',   dirname(dirname(__DIR__)) );
// Path to your application's private files. This can be same as PUBLIC_APP_PATH or some other directory outside .../public_html
defined( 'PRIVATE_APP_PATH' )   || define( 'PRIVATE_APP_PATH',  PUBLIC_APP_PATH );

// Some application specific directories, usually kept in the private directory. 
defined( 'APP_PATH' )           || define( 'APP_PATH',          PRIVATE_APP_PATH . '/src/app' );
defined( 'VENDOR_PATH' )        || define( 'VENDOR_PATH',       PRIVATE_APP_PATH . '/src/vendor' );
defined( 'ROUTES_PATH' )        || define( 'ROUTES_PATH',       PRIVATE_APP_PATH . '/src/app/routes' );
defined( 'CONFIG_PATH' )        || define( 'CONFIG_PATH',       PRIVATE_APP_PATH . '/src/app/config' );
defined( 'LOG_PATH' )           || define( 'LOG_PATH',          PRIVATE_APP_PATH . '/src/log' );

/**
 * Include composer's autoload
 */
include VENDOR_PATH . DIRECTORY_SEPARATOR . 'autoload.php';

$__ = [];

/**
 * Grab the config.php and use those settings to create the Slim application. 
 */
$__['cfgPath'] = CONFIG_PATH;
if ( is_dir("{$__['cfgPath']}.local") ) {
    $__['cfgPath'] .= '.local';
} 
$__['config'] = include( $__['cfgPath'] . DIRECTORY_SEPARATOR . 'config.php' );

$APP = new \Slim\App([ 'settings' => $__['config'] ]);

/**
 * Get any other configuration files and inject the depencencies
 * in the container. 
 * Each configuration file must return a closure. Use the config/logger.php 
 * file as example. 
 */
$__['container'] = $APP->getContainer();
$__['dir'] = scandir( $__['cfgPath'] );
foreach( $__['dir'] as $__fileName ) {
    if ( (substr( $__fileName, 0, 1 ) == '.') || ( $__fileName == 'config.php' ) ) continue;
    $__fullFileName = "{$__['cfgPath']}/{$__fileName}";
    if ( is_readable($__fullFileName) ) {
        $__key = \A3gZ\Inflector\Inflector::toCamelBack(substr( $__fileName, 0, -4 )); // Remove the file extension
        $__['container'][$__key] = include $__fullFileName;
    }
}

if ( !is_readable( 'bootstrap.php' ) ) {
    throw new \Exception( 'Missing file: ' . realpath(__DIR__ . '/bootstrap.php') );
}
include 'bootstrap.php';

/**
 * Import routes. 
 * Routes are expected to be defined in files below app-name/src/app/routes/
 *
 * It is also possible to split routes among sub-directories under .../routes/
 * For this to work, define the constant DEEP_ROUTES in your local config as true.
 *
 * routes.php: 
 * ------------------------------------------------------------------------- 
 * include './sales/orders.php';        // Define routes related to Orders
 * include './sales/products.php';      // Define routes related to Products
 */
$__['path'] = ROUTES_PATH;
__chubbyIncludeRoutes( $__['path'] );
function __chubbyIncludeRoutes( $basePath ) {
    global $APP;

    $__['dir'] = scandir( $basePath );

    foreach( $__['dir'] as $__fileName ) {
        if ( substr( $__fileName, 0, 1 ) == '.' ) continue;

        $__['fullFileName'] = realpath("{$basePath}/{$__fileName}");
        $__['ignore'] = file_exists(realpath("{$basePath}/.ignore"));
        if ( is_dir($__['fullFileName']) ) {
            __chubbyIncludeRoutes( $__['fullFileName'] );
        } elseif ( is_readable($__['fullFileName']) && !$__['ignore'] ) {
            include $__['fullFileName'];
        }
        
    }
} // __chubbyIncludeRoutes()

unset($__); // Destroy all the temporary global variables.

$APP->run(); 

// EOF