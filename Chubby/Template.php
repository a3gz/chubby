<?php 
/**
 * Chubby Framework (http://www.roetal.com/chubby-framework)
 *
 * @link      https://github.com/a3gz/Chubby
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/chubby-framework/license (MIT License)
 */
namespace Chubby;

class Template extends View
{
    /**
     * $theme 
     *
     * @var \Chubby\Theme The theme to use when rendering.
     */
    protected $theme = null;

    
    /**
     *
     */
    public function getTheme()
    {
        if ( $this->theme == null )
        {
            $this->theme = new \Chubby\Theme();
        } 
        return $this->theme;
    } // getTheme()

    
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
        if ( !preg_match( "#^([^\:\\\/]+):([^\:]+)$#", $ref, $parts ) )
        {
            throw new \Exception( "Invalid template reference: {$ref}" );
        }
        
        $moduleName = $parts[1];
        $templateFilename = $parts[2] . '.php';
        
        $this->filename = ''; // Reset to override any previously used template
        
        $fullPath = APP_PATH . DS . 'Themes' . DS . $this->getTheme()->getName() . DS . 'Templates' . DS . $templateFilename;
        if ( !is_readable( $fullPath ) )
        {
            $fullPath = APP_PATH . DS . 'Themes' . DS . 'Default' . DS . 'Templates' . DS . $templateFilename;
            if ( !is_readable( $fullPath ) )
            {
                throw new \Exception( "Cannot find a template from the given reference: {$ref}" );
            }
        }
        $this->filename = $fullPath;
        
        return $this;
    } // using()
    
    
    /**
     *
     */
    public function wearingTheme( \Chubby\Theme $theme )
    {
        $this->theme = $theme;
        
        return $this;
    } // wearingTheme()
    
} // class 

// EOF 
