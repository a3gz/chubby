<?php
return [
  'errorHandler' => [
    'displayErrorDetails' => true,
    'logError' => true,
    'logErrorDetails' => true,
  ],
  'notFoundHandler' => function ($container) {
    return \Plugins\demo\controllers\NotFountExceptionHandler::class . ':notFound';
  },
  // 'methodNotAllowedHandler' => function($container) {
  //   return \Fat\Handlers\MethodNotAllowedExceptionHandler::class . ':methodNotAllowed';
  // },
];

// EOF
