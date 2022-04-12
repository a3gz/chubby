<?php

namespace Plugins\demo\templates;

class DefaultTemplate extends \Chubby\View\Template {
  protected $components = [
    'header'    => 'views/components/header.php',
    'footer'    => 'views/components/footer.php',
  ];

  protected $template = 'templates/default-template.php';
}

// EOF