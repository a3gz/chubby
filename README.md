# Chubby
Adding some fat to [Slim Framework](https://github.com/slimphp/Slim).

Chubby provides a working Slim application template around which additional fat is added in the form of modules. 

The first of these modules is [Chubby View](https://github.com/a3gz/chubby-view), a PHP renderer that offers a very convenient way of organizing code. 

## Install via Composer 

Go to the directory where you want the new application to sit, then create the project: 

    composer create-project a3gz/chubby -s dev

This will create a new project called `chubby`.

Once the project has been created you can safely delete the `chubby/composer.json` file and the `chubby/vendor` directory. **Don't delete the file `chubby/src/composer.json` thought, this one is wher you add your dependencies**.

Chubby needs all required dependencies to sit on the `src` directory:

    > cd chubby/src
    > composer install

Finally go to your browser and request: 

    .../chubby/hello/world


It's very unlikely that you'll want your application to be called `chubby`, so you may want to rename that directory. 

It is also possible to do this when you create the project: 

    composer create-project a3gz/chubby my-app -s dev

Now your application is in `./my-app`.

## Why Chubby at all?

Chubby is a working application template that offers one possible implementation of the application structure proposed in Slim's documentation. 

Around that idea Chubby sets the foundations to split the application files in a way that the code can be placed outside the `public_html` directory, among other things. 

### Configuration

Chubby assumes the existence of a `src/app/config` directory containing at least one file called `config.php`. This file should return an associateive array with settings that will be passed to the Slim application's constructor. 

Optionally we can provide Slim with additional dependencies by adding more files inside the `config` directory. Each file must return one dependency. Take the provided `logger.php` for instance: 

    return function($c) {
        $appName =  basename(PUBLIC_APP_PATH);
        $logFileName = dirname(__DIR__) . "/{$appName}.local.log"; 
        $logger = new \Monolog\Logger($appName);
        $file_handler = new \Monolog\Handler\StreamHandler( $logFileName );
        $logger->pushHandler($file_handler);
        return $logger;   
    };

Chubby will inject the dependency in Slim's container under the same name as the file, in this case: `logger`: `$container['logger']`.

### Organizing routes

Chubby assumes that all our routes are sitting on `src/app/routes`. Inside this directory we can create as many files and sub-directories as we need.

## Customizing the environment 

It is possible to change everything that's assumed by chubby by re-defining the path constants to adapt to specific needs. This is what the file `local.php` is for.

## Contact the author

I would welcome comments, suggestions and brainstorming-like ideas.

[alejandro@roetal.com](mailto:alejandro@roetal.com)