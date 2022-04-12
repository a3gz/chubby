<?php

/**
 * @copyright Copyright (c) Alejandro Arbiza
 * @license   http://www.roetal.com/license/mit (MIT License)
 */

namespace Fat\Helpers;

class Template {
  protected $basePath;
  protected $components = [];
  protected $fallbackTheme = 'default';
  protected $renderedComponents = [];

  protected $bundledStyles = [];
  protected $bundledJavascript = [];
  protected $requiredStyles = [];
  protected $requiredJavascript = [];

  /**
   * $data
   *
   * @var array $data Array of (key => value) pars having used in the view.
   */
  protected $data = [];

  protected $helpers = [];

  protected $template;

  /**
   * $styles
   *
   * @var array Code to be injected into the page head
   */
  protected $styles = [];

  /**
   * $scripts
   *
   * @var array Scripts to be injected in the DOM
   */
  protected $scripts = [];

  /**
   * $placeholders
   *
   * @var array Pre-defined custom placeholders
   */
  protected $placeholders = [
    'chubby-scripts' => [],
    'chubby-styles' => [],
  ];

  public function __construct($basePath = null) {
    $this->basePath = rtrim($basePath, '/');
    // Merge components from the child class
    $class = get_called_class();
    $components = get_class_vars($class)['components'];
    foreach ($components as $name => $path) {
      $this->define($name, $path);
    }
    // Complete the template file name
    $this->template = $this->getPreparedPath($this->template);
  }

  public function __call($key, $args) {
    if (isset($this->helpers[$key]) && is_callable($this->helpers[$key])) {
      return call_user_func_array($this->helpers[$key], $args);
    }
  }

  /**
   * Getter to allow views to access the data passed in for them.
   *
   * @param string $key The variable name.
   * @return mixed The given value.
   */
  public function __get($key) {
    if (isset($this->data[$key])) {
      return $this->data[$key];
    }
    if (isset($this->helpers[$key])) {
      return $this->helpers[$key];
    }
  }

  public function __isset($key) {
    return isset($this->data[$key]);
  }

  public function addHelper($key, $value) {
    $key = str_replace(' ', '_', $key);
    $this->helpers[$key] = $value;
    return $this;
  }


  public function bundleJs($pathsArray) {
    if (!is_array($pathsArray)) {
      $pathsArray = [$pathsArray];
    }
    foreach ($pathsArray as $path) {
      $absFilename = $path;
      if (!is_readable($absFilename)) {
        break;
      }
      if (!isset($this->bundledJavascript[$path])) {
        $this->bundledJavascript[$path] = 'ok';
        ob_start();
        echo '<chubby-scripts><script>';
        echo "\n/** Bundle JS: {$path} */\n";
        echo file_get_contents($absFilename);
        echo "\n</script></chubby-scripts>";
        $buff = ob_get_contents();
        ob_end_clean();
        echo $buff;
      }
    }
  }

  public function bundleCss($pathsArray) {
    if (!is_array($pathsArray)) {
      $pathsArray = [$pathsArray];
    }
    foreach ($pathsArray as $path) {
      $absFilename = $path;
      if (!is_readable($absFilename)) {
        break;
      }
      if (!isset($this->bundledStyles[$path])) {
        $this->bundledStyles[$path] = 'ok';
        ob_start();
        echo '<chubby-styles><style>';
        echo "\n/** Bundle CSS: {$path} */\n";
        echo file_get_contents($absFilename);
        echo "\n</style></chubby-styles>";
        $buff = ob_get_contents();
        ob_end_clean();
        echo $buff;
      }
    }
  }

  /**
   * Register a component in the template so later we can render it via the
   * name as in $this->render('nice-name').
   *
   * @param string $name
   * @param string $path
   * @return $this
   */
  public function define($name, $path) {
    if (!preg_match('#^.+(.php|.html)$#', $path)) {
      $path .= '.php';
    }
    $fallbackTheme = $this->getFallbackTheme();
    $themeName = $this->getThemeName();
    $themePath = "themes/{$themeName}/{$path}";
    $prepared = $this->getPreparedPath($themePath);
    if (!is_readable($prepared)) {
      if ($themeName !== $fallbackTheme) {
        $themePath = "themes/{$fallbackTheme}/{$path}";
        $prepared = $this->getPreparedPath($themePath);
      }
      if (!is_readable($prepared)) {
        throw new \Exception("The component `{$name}` could not be found at `{$path}`");
      }
    }
    $this->components[$name] = $themePath;
    return $this;
  }

  public function defineRendered($name, $content) {
    $this->renderedComponents[$name] = $content;
    return $this;
  }

  protected function getFallbackTheme() {
    return $GLOBALS['hooks']->apply_filters(
      'chubby_fallback_theme',
      $this->fallbackTheme
    );
  }

  protected function getPreparedPath($path) {
    if (substr($path, 0, 1) != '/') {
      $basePath = '';
      if (!empty($this->basePath)) {
        $basePath = $this->basePath . '/';
      }
      $path = realpath($basePath . $path);
    }
    return $path;
  }

  public function getThemeName() {
    $fallbackTheme = $this->getFallbackTheme();
    return $GLOBALS['hooks']->apply_filters(
      'chubby_theme',
      $fallbackTheme
    );
  }

  public function isDefined($name) {
    return isset($this->components[$name]);
  }

  /**
   * Scan views for placeholders.
   *
   * @param string $view An included and PHP processed view.
   *
   * @return string The modified view, stripped from the special content.
   */
  private function preProcessComponent($input) {
    $output = $input;
    $placeholders = array_keys($this->placeholders);
    foreach ($placeholders as $placeholder) {
      $otag = "<{$placeholder}>";
      $ctag = "</{$placeholder}>";
      do {
        $tagBegin = stripos($output, $otag);
        if ($tagBegin !== false) {
          $tagEnd = strpos($output, $ctag, $tagBegin);
          if ($tagEnd === false) {
            throw new \Exception(
              "Invalid view: closing tag missing for {$placeholder}"
                . print_r($output, true)
            );
          }
          $htmlStart = $tagBegin + strlen($otag);
          $htmlLen = $tagEnd - $htmlStart;
          $html = substr($output, $htmlStart, $htmlLen);

          $this->placeholders[$placeholder][] = $html;
          $output = substr($output, 0, $tagBegin)
            . substr($output, $tagEnd + strlen($ctag));
        }
      } while ($tagBegin !== false);
    }
    return $output;
  }

  public function requireJs($pathsArray) {
    if (!is_array($pathsArray)) {
      $pathsArray = [$pathsArray];
    }

    ob_start();
    echo '<chubby-scripts>';
    foreach ($pathsArray as $path) {
      if (substr($path, 0, 4) !== 'http') {
        continue;
      }
      $hash = md5($path);
      if (!isset($this->requiredJavascript[$hash])) {
        $this->requiredJavascript[$hash] = 'ok';
        echo "<script src=\"{$path}\"></script>\n";
      }
    }
    echo "\n</chubby-scripts>";
    $buff = ob_get_contents();
    ob_end_clean();
    echo $buff;
  }

  public function requireCss($pathsArray) {
    if (!is_array($pathsArray)) {
      $pathsArray = [$pathsArray];
    }

    ob_start();
    echo '<chubby-styles>';
    foreach ($pathsArray as $path) {
      if (!isset($this->requiredStyles[$path])) {
        $this->requiredStyles[$path] = 'ok';
        echo "<link rel=\"stylesheet\" href=\"{$path}\" />\n";
      }
    }
    echo "\n</chubby-styles>";
    $buff = ob_get_contents();
    ob_end_clean();
    echo $buff;
  }

  public function registerPlaceholder($placeholder) {
    if (!isset($this->placeholders[$placeholder])) {
      $this->placeholders[$placeholder] = [];
    }
    return $this;
  }

  /**
   * Renders a component.
   * If $component is a key predefined via Template::define() the registered path
   * is used. If $component is a readable file, it is used.
   *
   * @param string $component A previouly defined
   * @param mixed $data Additional data passed to the renderer at render-time
   */
  public function render($component, $data = null) {
    $component = trim($component);
    $componentFileName = $component;
    $componentContent = null;
    if (isset($this->components[$component])) {
      $componentFileName = $this->components[$component];
    } elseif (isset($this->renderedComponents[$component])) {
      $componentContent = $this->renderedComponents[$component];
    }
    if ($componentContent === null) {
      $componentFileName = $this->getPreparedPath($componentFileName);
      if (!is_readable($componentFileName)) {
        throw new \Exception("Unable to render `{$component}` (`{$componentFileName}`).");
      }
      // Export data to the output
      foreach ($this->data as $__key => $__value) {
        $$__key = $__value;
      }

      if (($data != null) && is_array($data)) {
        foreach ($data as $__key => $__value) {
          $$__key = $__value;
        }
      }
    }

    try {
      ob_start();
      if ($componentContent === null) {
        include $componentFileName;
      } else {
        echo $componentContent;
      }
      $html = ob_get_contents();
      $html = $this->preProcessComponent($html);
      ob_end_clean();
    } catch (\Exception $e) {
      echo $e;
      die();
    }
    echo $html;
  }

  /**
   * Sets data that will be available in the views.
   * @param array $data An array of key=>value pairs.
   */
  public function setData(array $data) {
    foreach ($data as $key => $value) {
      $this->data[$key] = $value;
    }
    return $this;
  }

  public function getView() {
    $buffer = 'empty-view';
    if (is_readable($this->template)) {
      ob_start();
      include $this->template;
      $buffer = ob_get_contents();
      ob_end_clean();

      // Inject custom placeholders content into the final page.
      foreach ($this->placeholders as $placeholder => $content) {
        if (!count($content)) continue;

        $tagsCandidates = [
          "<{$placeholder}></{$placeholder}>",
          "<{$placeholder}/>",
          "<{$placeholder} />",
        ];

        $ti = 0;
        do {
          $tagx = $tagsCandidates[$ti];
          $tagPos = stripos($buffer, $tagx);
          $ti += 1;
        } while ($tagPos === false && $ti < 3);

        if ($tagPos !== false) {
          $tagLen = strlen($tagsCandidates[$ti - 1]);
          if (count($content)) {
            $html = implode("\n", $content);
            $buffer = substr($buffer, 0, $tagPos)
              . $html
              . substr($buffer, $tagPos + $tagLen);
          }
        }
      }
    }
    return $buffer;
  }

  /**
   * Get a response, prepares and injects the body (html or other) and returns the modified response object
   * ready to be rendered.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   * @param mixed $content Optional custom content
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function write(\Psr\Http\Message\ResponseInterface $response, $content = null) {
    $body = $response->getBody();
    if ($content === null) {
      $content = $this->getView();
    }
    $body->write($content);
    $response = $response->withBody($body);
    return $response;
  }
} // class

// EOF
