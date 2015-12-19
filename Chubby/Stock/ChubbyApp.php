<?php 
/**
 * This file is part of Chubby Framework.
 *
 * Chubby Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation version 3 of the License.
 *
 * Chubby Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Chubby Framework.    
 * If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright (c) Alejandro Arbiza
 * @author Alejandro Arbiza <alejandro@roetal.com>
 */ 
namespace Chubby\Stock;

use League\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class WebApp extends ChubbyApp 
{
	/**
	 * $router
	 */
	protected $router = null;
	
	
	/**
	 *
	 */
	public function getRouter()
	{
		if ( ($this->router == null) || !( $this->router instanceof League\Route\RouteCollection ) )
		{
			$this->router = new League\Route\RouteCollection;
		}
		
		return $this->router;
	} // getRouter()
	
	
	/**
	 *
	 */
	protected function extendedModuleInit( \Chubby\Stock\ChubbyModule $module )
	{
		$module->registerRoutes( $this->getRouter() );
	} // extendedModuleInit()
	
	
	/**
	 *
	 */
	public function run( string $appNamespace = \Chubby\Stock\ChubbyApp::DEFAULT_NAMESPACE )
	{
		parent::run();
		
		$dispatcher = $this->router->getDispatcher();

		$request = Request::createFromGlobals();

		$response = $dispatcher->dispatch( $request->getMethod(), $request->getPathInfo() );

		$response->send();		
		
		return $this;
	} // run()
	

} // class 

// EOF