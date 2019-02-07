<?php
namespace Templates;

class DefaultTemplate extends \Chubby\View\Template
{
  protected $components = [
      'header'    => 'views/components/header.php',
      'footer'    => 'views/components/footer.php',
  ];

  protected $template = 'views/templates/default-template.php';
} // class

// EOF