<?php

namespace Fat\Factory;

class HelpersFactory {
  public static function makePluginsLoader() {
    return new \Fat\Helpers\PluginsLoader();
  }
}

// EOF
