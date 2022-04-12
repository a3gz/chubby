# Chubby
Adding some fat to [Slim Framework](https://github.com/slimphp/Slim).

Chubby provides a working Slim application template around which additional fat is added in two ways: (1) useful classes under the `fat` directory and (2) as modules. 

The first of these modules is [Chubby View](https://github.com/a3gz/chubby-view), a renderer that proposes a very convenient way of organizing code. 

## Install via Composer 

Go to the directory where you want the new application to sit, then create the project: 

    composer create-project a3gz/chubby -s dev

This will create a new project called `chubby`.

Once the project has been created you can safely delete the `chubby/composer.json` file and the `chubby/vendor` directory. **Don't delete the file `chubby/private/composer.json` thought, this one is wher you add your dependencies**.

Chubby needs all required dependencies to sit on the `private` directory:

    > cd chubby/private
    > composer install

Finally go to your browser and request: 

    .../chubby/hello/world


It's very unlikely that you'll want your application to be called `chubby`, so you may want to rename that directory. 

It is also possible to do this when you create the project: 

    composer create-project a3gz/chubby my-app -s dev

Now your application is in `./my-app`.

### Running with Docker Compose

    docker-compose up [-d]

The provided `docker-compose.yml` maps to the host's 9999 port so you should be able to see the site in the following local address:

    http://localhost:9999

## Console request

    php console.php path/to/resource

## Why Chubby at all?

Chubby is a working application template that offers one possible way to organize a Slim application. 

Around that idea Chubby sets the foundations to split the application files in a way that the code can be placed outside the `public_html` directory, among other things. 

### Configuration

Chubby assumes the existence of a `private/app/config` directory containing two directories:
`config/container` and `config/settings`.
Under `config/container` there must extist at least one file called `main.php`. This file should return an associateive array with settings that will be injected in the container. 

Optionally we can inject additional dependencies by adding more files inside the `config/container` directory. Each file must return one dependency. Take the provided `logger.php` for instance: 

    return function ($c) {
      $time = time();
      $year = date('Y', $time);
      $month = date('m', $time);
      $day = date('d', $time);
      $hour = date('H', $time);
      $baseDir = \Fat\Helpers\Path::makePrivatePath('/logs'
        . '/' . $year
        . '/' . $month
        . '/' . $day
      );
      if (!is_dir($baseDir)) {
        mkdir($baseDir, 0777, true);
      }
      $fileName = "{$year}m{$month}d{$day}h{$hour}.log";
      $logFileName = "{$baseDir}/{$fileName}";

      $logger = new \Monolog\Logger($appName);
      $file_handler = new \Monolog\Handler\StreamHandler($logFileName);
      $logger->pushHandler($file_handler);
      return $logger;
    };

Chubby will inject the dependency in the container under the same name as the file, in this case: `logger`: `$container['logger']`.

## Slim 4

Chubby version `^3` depends on Slim `4.3.0` to keep PHP requirement down to `PHP 7.1`.
If a higher version of PHP is available, changing Slim dependency version to `^4` should work since we are still in the same major version... but I haven't tried this yet.

This version is more opinionated that Chubby `^1` because some of the things that were taken care of by Slim are now under our control.
The new directory `src/fat` contains classes that help with:

 * Initiating the `App`.
 * Adding an error handler.
 * Solving `HttpNotFoundException` in a way that makes it easy to customize by simply editing `config.php`.
 * Bringing back Slim3's `Environment::mock()` to make console requests possible by mocking an HTTP request.

## Hooks

Version 3 includes hooks and plugins support. Plugins are expected to extist under `private/app/plugins`. Each plugin must have a file called the same as the plugin's directory. This file must include a header section with at least one entry: `type` which value must be `Plugin` for the plugins loader to recognize it as such.
Check the demo plugin to see how it works.

## Contact the author

I would welcome comments, suggestions and brainstorming-like ideas.

[alejandro@roetal.com](mailto:alejandro@roetal.com)