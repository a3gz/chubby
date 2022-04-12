<?php

namespace Plugins\demo\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fat\Factory\AppFactory;
use Fat\Helpers\Path;
use Plugins\demo\templates\DefaultTemplate;

class HelloController {
  public function hello(Request $request, Response $response) {
    $app = AppFactory::getApp();
    $logger = $app->getContainer()->get('logger');
    if (isset($_GET['theme'])) {
      $GLOBALS['hooks']->add_filter('chubby_theme', function () {
        return $_GET['theme'];
      });
    }
    $name = $request->getAttribute('name');
    $logger->notice("Hello {$name}!");
    $tpl = new DefaultTemplate(Path::makePluginsPath('/demo'));
    $tpl->define('content', "views/components/hello")
      ->setData(['name' => $name])
      ->write($response);
    return $response;
  }
}

// EOF
