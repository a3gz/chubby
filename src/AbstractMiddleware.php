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
     *
     */
	public function __invoke( ServerRequestInterface $request, ResponseInterface $response, $next ) 
	{
        $this->request = $request;
        $this->response = $response; 
        
        $this->slim = \Chubby\AppFactory::getApp()->getSlim();
        $this->container = $this->slim->getContainer();
        
        $this->runBeforeNext();
        
		$response = $next( $this->request, $this->response);
        
        $this->runAfterNext();
        
		return $response;
	} // __invoke()
    
    
    /**
     * Called after the next middleware is invoked.
     */
    public abstract function runAfterNext();
    
    
    /**
     * Called before the next middleware is invoked.
     */
    public abstract function runBeforeNext();
    
} // class 

// EOF 