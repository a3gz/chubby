# Chubby
Adding some fat to [Slim Framework](https://github.com/slimphp/Slim).

Chubby is an application template and a collection of modules designed to work with Slim 3. 

Although the modules prefixed with `chubby` are designed to work with Slim, they need to be used in the context of a Chubby application because they assume the existence of the path constants defined by Chubby.

Chubby takes the proposed application structure in [Slim's documentation](https://www.slimframework.com/docs/tutorial/first-app.html) and provides a working template around which additional fat is added in the form of composer modules. 

The first of these modules is [Chubby View](https://github.com/a3gz/chubby-view), a PHP renderer that facilitates a very convenient way of organizing code. 

## Install via Composer 

Go to the directory where you want the new application to sit, then create the project: 

    composer create-project a3gz/chubby -s dev

This will create a project called `chubby`. 
Chubby needs all required dependencies to sit on the `src` directory:

    > cd chubby/src
    > composer install

Finally go to your browser and request: 

    .../chubby/src/public/hello/world


It's very unlikely that you'll want your application to be called `chubby`, so you may want to rename that directory. You can also do it when you create the project: 

    composer create-project a3gz/chubby my-app -s dev

Now your application is in `./my-app`.

## Why Chubby at all?

Chubby is a working template that offers one possible implementation of the application structure proposed in Slim's documentation. 

Around that idea Chubby sets the foundations to split the application files in a way that the code can be placed outside the `public_html` directory, among other things. 

### Configuration

Chubby assumes the existence of a `src/app/config` directory containing at least one file called `config.php`. This file should return an associateive array with settings that will be passed to the Slim application's constructor. 

Optionally we can provide Slim with additional dependencies by adding more files inside the `config` directory. Each file must return one dependency. Take the provided `logger.php` for instance: 

    return function($c) {
        $logFileName = PRIVATE_APP_PATH . DIRECTORY_SEPARATOR . basename(PRIVATE_APP_PATH) . '.log'; 
        $logger = new \Monolog\Logger('pwless');
        $file_handler = new \Monolog\Handler\StreamHandler( $logFileName );
        $logger->pushHandler($file_handler);
        return $logger;   
    };

Chubby will inject the dependency in Slim's container under the same name as the file, in this case: `logger`: `$container['logger']`.

### Organizing routes

Chubby assumes that all our routes are sitting on `src/app/routes`. Inside this directory we can create as many files and sub-directories as we need.

## Contact the author

I would welcome comments, suggestions and brainstorming-like ideas.

[alejandro@roetal.com](mailto:alejandro@roetal.com)