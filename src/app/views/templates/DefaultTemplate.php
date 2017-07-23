<?php 
namespace Templates;

class DefaultTemplate extends \Chubby\View\Template 
{
    /**
     * @var array
     */
    protected $components = [
        'header'    => 'src/app/views/components/header.php',
        'footer'    => 'src/app/views/components/footer.php',
    ];

    /**
     * @var string 
     */
    protected $template = 'src/app/views/templates/default-template.php';
} // class

// EOF