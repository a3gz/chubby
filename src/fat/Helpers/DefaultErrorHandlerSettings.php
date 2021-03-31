<?php

namespace Fat\Helpers;

class DefaultErrorHandlerSettings {
  static public function asArray() {
    return [
      'displayErrorDetails' => true,
      'logErrors' => true,
      'logErrorDetails' => true,
    ];
  }
}