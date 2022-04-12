<?php

/**
 * name: Chubby demo
 * type: Plugin
 */

$GLOBALS['hooks']->add_filter('chubby_routes', function ($locations) {
  $locations[] = __DIR__ . '/routes';
  return $locations;
});

// EOF
