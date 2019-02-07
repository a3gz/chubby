<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$APP->get('/hello/{name}', function (Request $request, Response $response) {
  $name = $request->getAttribute('name');
  $this->logger->notice("Hello {$name}!");
  $tpl = new \Templates\DefaultTemplate(realpath(dirname(__DIR__)));
  $tpl->define('content', 'views/components/hello')
    ->setData(['name' => $name])
    ->write( $response );
})->setName('hello');

// EOF
