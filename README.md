# Chubby
Adding some fat to [Slim Framework](https://github.com/slimphp/Slim).

Chubby is a wrapper for Slim Framework that proposes a way to organize the code. The main goals are:

* **Slim at core**. The very first and important goal is to take advantage of Slim's power while staying out of its way. Chubby proposes one way to organize the source code and provides one solution to some common tasks, but at the end we are always dealing directly with Slim. 

* **Modules**. The source files are organized as a set of semi-self-contained modules. Each module contains its own controllers, models, views, client-side controllers and specific assets as well as middleware and any other source of callables.

A minimal application's tree structure would look something like this: 

    private_html
        MyChubbyApp
            Modules
                Main
                    MainModule.php
                    Controllers
                    Models
                    Views
                    Assets
                    ...
                Blog
                    BlogModule.php
                    Controllers
                    Models
                    Views
                    Assets
                    ...
  
The module `Main` is a required special module. It is in this module where the instance of Slim is created and initialized. 

**A note about source location**
In the tree structure shown above the root directory is `private_html` instead of `public_html`. This is so because I personally prefer to locate all my source code outside the `public_html` directory, but this is not a requirement.
