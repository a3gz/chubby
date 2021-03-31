<?php
error_reporting(E_ALL & ~E_NOTICE);

return [
  'errorHandler' => [
    'displayErrorDetails' => true,
    'logError' => true,
    'logErrorDetails' => true,
  ],
  // 'notFoundHandler' => function($container) {
  //   return \Fat\Handlers\NotFountExceptionHandler::class . ':notFound';
  // },
  // 'methodNotAllowedHandler' => function($container) {
  //   return \Fat\Handlers\MethodNotAllowedExceptionHandler::class . ':methodNotAllowed';
  // },
];
// EOF