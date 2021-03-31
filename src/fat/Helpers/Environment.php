<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2017 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/3.x/LICENSE.md (MIT License)
 */

namespace Fat\Helpers;

class Environment {
  static public function mock(array $data) {
    if ((isset($data['HTTPS']) && $data['HTTPS'] !== 'off') ||
      ((isset($data['REQUEST_SCHEME']) && $data['REQUEST_SCHEME'] === 'https'))
    ) {
      $defscheme = 'https';
      $defport = 443;
    } else {
      $defscheme = 'http';
      $defport = 80;
    }

    $_SERVER = array_merge([
      'SERVER_PROTOCOL'      => 'HTTP/1.1',
      'REQUEST_METHOD'       => 'GET',
      'REQUEST_SCHEME'       => $defscheme,
      'SCRIPT_NAME'          => '',
      'REQUEST_URI'          => '',
      'QUERY_STRING'         => '',
      'SERVER_NAME'          => 'localhost',
      'SERVER_PORT'          => $defport,
      'HTTP_HOST'            => 'localhost',
      'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
      'HTTP_ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
      'HTTP_USER_AGENT'      => 'Slim Framework',
      'REMOTE_ADDR'          => '127.0.0.1',
      'REQUEST_TIME'         => time(),
      'REQUEST_TIME_FLOAT'   => microtime(true),
    ], $data, $_SERVER);
  }

  static public function mockConsole(array $data = []) {
    $argv = $GLOBALS['argv'];
    array_shift($argv);
    $pathInfo = $argv[0];
    if (substr($pathInfo, 0, 1) !== '/') {
      $pathInfo = '/' . $pathInfo;
    }
    self::mock(array_merge($data, ['REQUEST_URI' => $pathInfo]));
  }
}

// EOF