<?php

namespace Plugins\demo\templates;

class DefaultTemplate extends \Fat\Helpers\Template {
  protected $components = [
    'header'    => 'views/components/header.php',
    'footer'    => 'views/components/footer.php',
  ];

  protected $template = 'templates/default-template.php';
}

// EOF