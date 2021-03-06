<?php

namespace Fat\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Factory\AppFactory as SlimAppFactory;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Fat\App;

class AppFactory {
  protected $slim;

  static public function create() {
    $instance = self::instance();
    return $instance->getSlim();
  }

  protected function createSlimApp() {
    $container = new \DI\Container();
    $this->loadConfig($container);

    $responseFactory = $container->has(ResponseFactoryInterface::class)
      ? $container->get(ResponseFactoryInterface::class)
      : SlimAppFactory::determineResponseFactory();
    $callableResolver = $container->has(CallableResolverInterface::class)
        ? $container->get(CallableResolverInterface::class)
        : null;
    $routeCollector = $container->has(RouteCollectorInterface::class)
        ? $container->get(RouteCollectorInterface::class)
        : null;
    $routeResolver = $container->has(RouteResolverInterface::class)
        ? $container->get(RouteResolverInterface::class)
        : null;
    $middlewareDispatcher = $container->has(MiddlewareDispatcherInterface::class)
        ? $container->get(MiddlewareDispatcherInterface::class)
        : null;

    $this->slim = new App(
      $responseFactory,
      $container,
      $callableResolver,
      $routeCollector,
      $routeResolver,
      $middlewareDispatcher
    );

    if ($container->has('appBasePath')) {
      $this->slim->setBasePath($container->get('appBasePath'));
    }
    if ($container->has('errorHandler')) {
      $errorHandlerSettings = $container->get('errorHandler');
    }
    if (!isset($errorHandlerSettings) || !is_array($errorHandlerSettings)) {
      $errorHandlerSettings = [];
    }
    $errorHandlerSettings = array_merge(
      [
        'displayErrorDetails' => true,
        'logErrors' => true,
        'logErrorDetails' => true,
      ],
      $errorHandlerSettings,
    );
    $errorMiddleware = $this->slim->addErrorMiddleware(
      $errorHandlerSettings['displayErrorDetails'],
      $errorHandlerSettings['logErrors'],
      $errorHandlerSettings['logErrorDetails']
    );

    if ($container->has('notFoundHandler')) {
      $errorMiddleware->setErrorHandler(
        HttpNotFoundException::class,
        $container->get('notFoundHandler')
      );
    }
    if ($container->has('methodNotAllowedHandler')) {
      $errorMiddleware->setErrorHandler(
        HttpMethodNotAllowedException::class,
        $container->get('methodNotAllowedHandler')
      );
    }
    return $this;
  }

  static public function getApp() {
    return self::instance()->getSlim();
  }

  public function getSlim() {
    return $this->slim;
  }

  static public function instance() {
    static $o = null;
    if ($o === null) {
      $o = new AppFactory();
      $o->createSlimApp();
    }
    return $o;
  }

  /**
   * Load config files
   * If loading order is important, we can prefix all the configuration files
   * (except for config.php) with a number and a dash, like so: 001-file1.php,
   * 002-file2.php, etc.
   * The prefix will be removed so only the part of the name after the first
   * dash will be considered as the file's real name.
   */
  protected function loadConfig($container) {
    $cfgRoot = CONFIG_PATH;
    $cfgFiles = scandir($cfgRoot);

    $mainConfigFile = "{$cfgRoot}/config.php";
    if (!is_readable($mainConfigFile)) {
      throw new \Exception(
        'Required config file is missing: src/app/config/config.php'
      );
    }
    $settings = include($mainConfigFile);
    foreach ($settings as $key => $value) {
      $container->set($key, $value);
    }

    sort($cfgFiles);
    foreach ($cfgFiles as $fileName) {
      if ((substr($fileName, 0, 1) == '.') || ($fileName == 'config.php')) {
        continue;
      }
      $absFileName = "{$cfgRoot}/{$fileName}";
      if (is_readable($absFileName)) {
        $fileName = preg_replace('#^[0-9]+-#', '', $fileName);
        $key = substr($fileName, 0, -4);
        $container->set($key, include $absFileName);
      }
    }
  }

  static public function loadRoutes($basePath) {
    $dir = scandir($basePath);
    foreach($dir as $fileName) {
      if (substr($fileName, 0, 1) == '.') continue;
      $absFileName = "{$basePath}/{$fileName}";
      if (is_dir($absFileName)) {
        if (!is_readable(realpath("{$absFileName}/.ignore"))) {
          self::loadRoutes($absFileName);
        }
      } elseif (is_readable($absFileName)) {
        include $absFileName;
      }
    }
  }
}

// EOF