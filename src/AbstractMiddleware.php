<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractMiddleware 
{
    /**
     * @var Slim\Container
     */
    protected $container; 
    
    /**
     * @var Psr\Http\Message\ServerRequestInterface
     */
    protected $request;
    
    /**
     * @var Psr\Http\Message\ResponseInterface
     */
    protected $response; 
    
    /**
     * @var Slim\App
     */
    protected $slim;
    
    
	/**
     * This general method provide impelemenatation classes two oportunities to break the middleware 
     * chain by returning a value from runBeforeNext() or runAfterNext(). 
     * If any of these functions return a value other than null, that value will be returned by the __invoke() itself.
     */
	public function __invoke( ServerRequestInterface $request, ResponseInterface $response, $next ) 
	{
        $this->request = $request;
        $this->response = $response; 
        
        $this->slim = \Chubby\AppFactory::getApp()->getSlim();
        $this->container = $this->slim->getContainer();
        
        $returned = $this->runBeforeNext();
        if ( $returned instanceof ResponseInterface ) {
            return $returned;
        }
        
		$this->response = $next( $this->request, $this->response);
        
        $returned = $this->runAfterNext();
        if ( $returned instanceof ResponseInterface ) {
            return $returned;
        }
        
		return $this->response;
	} // __invoke()
    
    
    /**
     * Called after the next middleware is invoked.
     */
    public function runAfterNext()
    {
        // dummy
    } // runAfterNext()
    
    
    /**
     * Called before the next middleware is invoked.
     */
    public function runBeforeNext()
    {
        // dummy
    } // runBeforeNext()
    
} // class 

// EOF 