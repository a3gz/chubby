<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

class View
{
    /**
     * $data 
     *
     * @var array $data Array of (key => value) pars having used in the view. 
     */
    protected $data = [];

    
    /**
     * $filename
     *
     * @var string The full path to the view file 
     */
    protected $filename = '';
    
    
    /**
     * $scripts
     *
     * @var \Chubby\ScrptSpecs[] An array of script specification objects
     */
    protected $scripts = [];
    
    
    /**
     * $styles
     *
     * @var \Chubby\StylesheetSpecs[] An array of stylesheet specification objects
     */
    protected $styles = [];    
    
    
    /**
     * $views 
     *
     * @var \Chubby\View[] An array of Chubby\View objects.
     */
    protected $views = [];
    
    
    /**
     *
     */
    public function __get( $key )
    {
        if ( isset($this->data[$key] ) )
        {
            return $this->data[$key];
        }
    } // __get()
    
    
    /**
     * When treated as a string a template object will return the result of including the referred file.
     */
    public function __toString()
    {
        if ( is_readable( $this->filename ) )
        {
            ob_start();
           
            include $this->filename;
            $buffer = ob_get_clean();
        }
        
        return $buffer;
    } // __toString()    
    
    
    /**
     *
     */
    public function getViews()
    {
        return $this->views;
    } // getViews()
    
    
    /**
     *
     */
    public function hasViews()
    {
        return ( count($this->views) > 0 );
    } // hasViews()
    
    
    
    
    /**
     * Adds a view to the template. 
     * @param mixed $view A string or an array of strings
     */
    public function importView( $views )
    {
        if ( !is_array($views) )
        {
            $views = [$views];
        }
        
        foreach( $views as $view )
        {
            if ( !is_string($view) )
            {
                throw new \Exception( get_class($this) . "::importView() expects a string or an array of strings" );
            }
            
            $parts = [];
            if ( !preg_match( "#^([^\:\\\/]+):([^\:]+)$#", $view, $parts ) )
            {
                throw new \Exception( "Invalid template reference: {$view}" );
            }
            
            $moduleName = $parts[1];
            $view = $parts[2];
            
            $index = $path = '';
            if (preg_match("/[\/a-zA-Z0-9]+[ ]+as[ ]+[a-zA-Z0-9]+/i", $view))
            {
                $parts = explode(' as ', $view);
                $path = trim($parts[0]);
                $index = trim($parts[1]);
            }
            else
            {
                $index = basename($view);
                $path = $view;
            }    

            $fullPath = APP_PATH . DS . 'Modules' . DS . $moduleName . DS . 'Views' . DS . $path . '.php';

            if ( !is_readable( $fullPath ) )
            {
                if ( !$moduleName != 'Main' )
                {
                    $fullPath = APP_PATH . DS . 'Modules' . DS . 'Main' . DS . 'Views' . DS . $path . '.php';
                }
                
                if ( !is_readable( $fullPath ) )
                {
                    throw new \Exception( "Cannot find the view: {$view}" );
                }
            }

            $this->views[$index] = $fullPath;
        }
        return $this;
    } // importView()

    
    /**
     * Renders a previously imported view.
     *
     * @param string $viewIndex A string index referencing a view in the local $views array
     */
    public function render( $viewIndex )
    {
        if ( isset($this->views[$viewIndex]) )
        {
            include $this->views[$viewIndex];
        }
        return $this;
    } // render()
    
    
    /**
     * Sets data that will be available in the views.
     * @param array $data An array of key=>value pairs.
     */
    public function setData( array $data )
    {
        foreach( $data as $key => $value )
        {
            $this->data[$key] = $value;
        }
        
        return $this;
    } // setData()
    
    
    /**
     * Setup the view file to use. 
     * The first component in $ref MUST be the a colon separated pair having MODULE_NAME:TEMPLATE_NAME without file extension.
     * This will translate into APP_PATH/Modules/MODULE_NAME/Views/Templates/TEMPLATE_NAME.php
     *
     * @param string $ref A (MODULE_NAME, TEMPLATE_NAME) pair separated by colon.
     *
     * @return Self
     */
    public function using( $path )
    {
        $this->filename = $path;
        
        return $this;
    } // using()
        
} // class 

// EOF 
