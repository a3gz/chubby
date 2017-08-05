<?php 
namespace Templates;

class DefaultTemplate extends \Chubby\View\Template 
{
    /**
     * @var array
     */
    protected $components = [
        'header'    => 'views/components/header.php',
        'footer'    => 'views/components/footer.php',
    ];

    /**
     * @var string 
     */
    protected $template = 'views/templates/default-template.php';
} // class

// EOF