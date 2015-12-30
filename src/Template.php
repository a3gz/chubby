<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */
namespace Chubby;

class Template 
{
    /**
     * $data 
     *
     * @var array $data Array of (key => value) pars having used in the view. 
     */
    protected $data = [];

    
    /**
     * $styles
     *
     * @var array Code to be injected into the page head
     */
    protected $styles = [];
    
    
    /**
     * $scripts
     *
     * @var array Scripts to be injected in the DOM 
     */
    protected $scripts = [];
    
    
    /**
     * $placeholders 
     *
     * @var array Pre-defined custom placeholders 
     */
    protected $placeholders = [
        'chubby-scripts' => [],
        'chubby-styles' => []
    ];
    
    
    /**
     * $theme 
     *
     * @var \Chubby\Interfaces\ThemeInterface The theme to use when rendering.
     */
    protected $theme = null;
    
    
    /**
     * $views 
     *
     * @var string[] An array of view paths.
     */
    protected $views = [];

    
    

    /**
     * Getter to allow views to access the data passed in for them.
     *
     * @param string $key The variable name.
     *
     * @return mixed The given value.
     */
    public function __get( $key )
    {
        if ( isset($this->data[$key] ) ) {
            return $this->data[$key];
        }
    } // __get()
 

    /**
     * When treated as a string a template object will return the result of including the referred file.
     */
    public function __toString()
    {
        if ( is_readable( $this->filename ) ) {
            ob_start();
           
                include $this->filename;
                
                $buffer = ob_get_contents();
                
            ob_end_clean();
            
            $dirty = false;

            $dom = \SunraDomParser\HtmlDomParser::fromString( $buffer );
            
            
            // Inject custom placeholders content into the final page.
            foreach( $this->placeholders as $placeholder => $content ) {
                if ( $domNode = $dom->find($placeholder, 0) ) {
                    $outerText = '';
                    if ( count($content) ) {
                        $outerText = implode($content, "\n"); 
                    }
                        
                    $domNode->outerText = $outerText;
                    $dirty = true;
                }
            }

            // Save changes to the DOM
            if ( $dom && $dirty ) {
                $buffer = $dom->save();
            } 
        }
        
        return $buffer;
    } // __toString()  


    /**
     * Returns the currently used theme. 
	 * Although it's not the template's responsibility to create the theme object, this method 
	 * creates one if no theme has been set. The reason for this is that we know that ultimately 
	 * we will fallback to the Default theme if everything else fails.
	 *
	 * @returns Chubby\Interfaces\ThemeInterface An instance to the currently used theme.
     */
    public function getTheme()
    {
		if ( $this->theme == null ) {
			$this->theme = new \Chubby\Theme('Default');
		}
        return $this->theme;
    } // getTheme()
    
    
    /**
     * Adds a view to the template. 
     * @param mixed $view A string or an array of strings
     */
    public function importView( $views )
    {
        if ( !is_array($views) ) {
            $views = [$views];
        }
        
        foreach( $views as $view ) {
            
            if ( !is_string($view) ) {
                throw new \Exception( get_class($this) . "::importView() expects a string or an array of strings" );
            }
            
            $parts = [];
            if ( !preg_match( "#^([^\:\\\/]+):([^\:]+)$#", $view, $parts ) ) {
                throw new \Exception( "Invalid template reference: {$view}" );
            }
            
            $moduleName = $parts[1];
            $view = $parts[2];
            
            $index = $path = '';
            
            if (preg_match("/[\/a-zA-Z0-9]+[ ]+as[ ]+[a-zA-Z0-9]+/i", $view)) {
                $parts = explode(' as ', $view);
                $path = trim($parts[0]);
                $index = trim($parts[1]);
            } else {
                $index = basename($view);
                $path = $view;
            }    

            $fullPath = APP_PATH . DS . 'Modules' . DS . $moduleName . DS . 'Themes' . DS . $this->getTheme() . DS . 'Views' . DS . $path . '.php';

            if ( !is_readable( $fullPath ) ) {
                if ( !$moduleName != 'Main' ) {
                    $fullPath = APP_PATH . DS . 'Modules' . DS . $moduleName . DS . 'Themes' . DS . 'Default' . DS . 'Views' . DS . $path . '.php';
                }
                
                if ( !is_readable( $fullPath ) ) {
                    throw new \Exception( "Cannot find the view: {$view}" );
                }
            }

            $this->views[$index] = $fullPath;
        }
        return $this;
    } // importView()
    
    
    /**
     * Scan views for placeholders, save the content and remove the placeholders from the view.
     *
     * @param string $view An included and PHP processed view. 
     *
     * @return string The modified view, stripped from the special content. 
     */
    private function preProcessView( $view )
    {
        $dom = \SunraDomParser\HtmlDomParser::fromString( $view );
        
        foreach( $this->placeholders as $placeholder => $content ) {
            // Search for head content 
            $nodes = $dom->find( $placeholder );
            
            foreach( $nodes as $node ) {
                $this->placeholders[$placeholder][] = $node->innerText();
                $node->outerText = '';
            }
        }
        
        $view = $dom->save();

        return $view;
    } // preProcessView()
    
    
    /**
     * Registers a new custom placeholder. 
     *
     * @param string $placeholder The custom placeholder to register.
     *
     * @return Chubby\Template Self
     */
    public function registerPlaceholder( $placeholder )
    {
        if ( !isset($this->placeholders[$placeholder]) ) {
            $this->placeholders[$placeholder] = [];
        }
        
        return $this;
    } // registerPlaceholder()

    
    /**
     * Renders a previously imported view.
     *
     * @param string $viewIndex A string index referencing a view in the local $views array
     */
    public function render( $viewIndex )
    {
        if ( isset($this->views[$viewIndex]) ) {
            ob_start();
                include $this->views[$viewIndex];
                $view = ob_get_contents();
                $view = $this->preProcessView( $view );
            ob_end_clean();
            echo $view;
        }
    } // render()
    
    
    /**
     * Sets data that will be available in the views.
     * @param array $data An array of key=>value pairs.
     */
    public function setData( array $data )
    {
        foreach( $data as $key => $value ) {
            $this->data[$key] = $value;
        }
        
        return $this;
    } // setData()


    /**
     * Sets the theme object. We use a setter to make sure the property gets the right type of value.
     *
     * @param Chubby\Interfaces\ThemeInterface $theme A theme.
	 *
	 * @return Chubby\Template Self
     */
    public function setTheme( \Chubby\Theme $theme )
    {
        $this->theme = $theme;
		return $this;
    } // setTheme()
    
    
    /**
     * Setup the template file to use. 
     * The first component in $ref MUST be the a colon separated pair having MODULE_NAME:TEMPLATE_NAME without file extension.
     * This will translate into APP_PATH/Modules/MODULE_NAME/Views/Templates/TEMPLATE_NAME.php
     *
     * @param string $ref A (MODULE_NAME, TEMPLATE_NAME) pair separated by colon.
     *
     * @return Self
     */
    public function using( $ref )
    {
        $parts = [];
        if ( !preg_match( "#^([^\:\\\/]+):([^\:]+)$#", $ref, $parts ) ) {
            throw new \Exception( "Invalid template reference: {$ref}" );
        }
        
        $moduleName = $parts[1];
        $templateFilename = $parts[2] . '.php';
        
        $this->filename = ''; // Reset to override any previously used template
        
        $fullPath = APP_PATH . DS . 'Templates' . DS . $this->getTheme()->name . DS . $templateFilename;
        
        if ( !is_readable( $fullPath ) ) {
            $fullPath = APP_PATH . DS . 'Templates' . DS . 'Default' . DS . $templateFilename;
            
            if ( !is_readable( $fullPath ) ) {
                throw new \Exception( "Cannot find a template from the given reference: {$ref}" );
            }
        }
        $this->filename = $fullPath;
        
        return $this;
    } // using()
    
    
    /**
     * @param string $themeName The theme name to use. 
     *
     * @return Chubby\Template Self
     */
    public function wear( $themeName )
    {
        $this->theme = new \Chubby\Theme( $themeName );
        
        return $this;
    } // wear()
    
} // class 

// EOF 
