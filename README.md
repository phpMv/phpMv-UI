![php-mv-UI](http://angular.kobject.net/git/phalconist/php-mv-ui-.png "phpMv-UI")

**Visual components library** (JQuery UI, Twitter Bootstrap, Semantic-UI) for php and php MVC frameworks

[phpMv-UI website](https://phpmv-ui.kobject.net/)

[![Build Status](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/badges/build.png?b=master)](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/build-status/master) [![Latest Stable Version](https://poser.pugx.org/phpmv/php-mv-ui/v/stable)](https://packagist.org/packages/phpmv/php-mv-ui) [![Total Downloads](https://poser.pugx.org/phpmv/php-mv-ui/downloads)](https://packagist.org/packages/phpmv/php-mv-ui) [![License](https://poser.pugx.org/phpmv/php-mv-ui/license)](https://packagist.org/packages/phpmv/php-mv-ui)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/1426105a-86b9-4ef3-ace3-d054a792e95d/mini.png)](https://insight.sensiolabs.com/projects/1426105a-86b9-4ef3-ace3-d054a792e95d) 
[![Documentation](https://codedocs.xyz/phpMv/phpMv-UI.svg)](http://api.kobject.net/phpMv-UI/)


## What's phpMv-UI ?

phpMv-UI is a visual components library for php : a php wrapper for jQuery and UI components (jQuery, Twitter Bootstrap, Semantic-UI).

Using the dependency injection, the jQuery object can be injected into **php framework container**, allowing for the generation of jQuery scripts in controllers, respecting the MVC design pattern.

## Requirements/Dependencies

* PHP >= 7.0
* JQuery >= 2.0.3
* JQuery UI >= 1.10 [optional]
* Twitter Bootstrap >= 3.3.2 [optional]
* Semantic-UI >= 2.2 or Fomantic-UI >= 2.7 [optional]

## Resources
* [API](https://api.kobject.net/phpMv-UI/)
* [Documentation/demo](https://phpmv-ui.kobject.net/)
* [Semantic-ui](https://semantic-ui.com) [Fomantic-ui](https://fomantic-ui.com)

## I - Installation

### Installing via Composer

Install Composer in a common location or in your project:

```bash
curl -s http://getcomposer.org/installer | php
```
Create the composer.json file in the app directory as follows:

```json
{
    "require": {
        "phpmv/php-mv-ui": "^2.3"
    }
}
```
In the app directory, run the composer installer :

```bash
php composer.phar install
```

### Installing via Github

Just clone the repository in a common location or inside your project:

```
git clone https://github.com/phpMv/phpMv-UI.git
```

## II PHP frameworks configuration
### Library loading
phpMv-UI complies with [PSR-4 recommendations](http://www.php-fig.org/psr/psr-4/) for auto-loading classes.
Whatever the php framework used, with "composer", it is enough to integrate the Composer autoload file.
```php
require_once("vendor/autoload.php");
```
### <img src="http://angular.kobject.net/git/images/phalcon.png" width="30"> Phalcon configuration

#### Library loading
Without Composer, It is possible to load the library with the **app/config/loader.php** file :

```php
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(array(
		'Ajax' => __DIR__ . '/../vendor/phpmv/php-mv-ui/Ajax/'
))->register();
```

#### Injection of the service

It is necessary to inject the JQuery service at application startup, in the service file **app/config/services.php**, and if necessary instantiate Semantic, Bootstrap or Jquery-ui :
```php
$di->set("jquery",function(){
    $jquery= new Ajax\php\phalcon\JsUtils();
    $jquery->semantic(new Ajax\Semantic());//for Semantic UI
    return $jquery;
});
```

#### Use in controllers
Example of creating a Semantic-UI button

```php
use Phalcon\Mvc\Controller;
use Ajax\php\phalcon\JsUtils;
    /**
     * @property JsUtils $jquery
    **/
class ExempleController extends Controller{
	public function indexAction(){
		$semantic=$this->jquery->semantic();
		$button=$semantic->htmlButton("btTest","Test Button");
		echo $button;
	}
}
```

### <img src="http://angular.kobject.net/git/images/ci.png" width="30"> CodeIgniter configuration
#### Library loading
If you want CodeIgniter to use a Composer auto-loader, just set `$config['composer_autoload']` to `TRUE` or a custom path in **application/config/config.php**.

Then, it's necessary to create a library for the JsUtils class

##### Library creation
Create the library **XsUtils** (the name is free) in the folder **application/libraries**

```php
use Ajax\php\ci\JsUtils;
class XsUtils extends Ajax\php\ci\JsUtils{
	public function __construct(){
		parent::__construct(["semantic"=>true,"debug"=>false]);
	}
}
```
#### Injection of the service
Add the loading of the **XsUtils** library in the file **application/config/autoload.php**

The jquery member will be accessible in the controllers
```php
$autoload['libraries'] = array('XsUtils' => 'jquery');
```
Once loaded you can access your class in controllers using the **$jquery** member:
```php
$this->jquery->some_method();
```

### ![](http://angular.kobject.net/git/images/laravel.png) Laravel configuration

#### Library loading
If you do not use the Composer autoloader file, you can also load phpMv-UI with composer.json :

```json
"autoload": {
    "classmap": [
        ...
    ],
    "psr-4": {
        "Ajax\\": "vendor/phpmv/php-mv-ui/Ajax"
    }
},
```
#### Injection of the service
Register a Singleton in **bootstrap/app.php** file :

```php
$app->singleton(Ajax\php\laravel\JsUtils::class, function($app){
	$result= new Ajax\php\laravel\JsUtils();
	$result->semantic(new Ajax\Semantic());
	return $result;
});
```

Then it is possible to inject the **JsUtils** class in the base class controllers constructor :

```php
use Ajax\php\laravel\JsUtils;
class Controller extends BaseController{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    protected $jquery;

    public function __construct(JsUtils $js){
    	$this->jquery = $js;
    }

    public function getJquery() {
    	return $this->jquery;
    }
}
```
### <img src="http://angular.kobject.net/git/images/yii.png" width="30"> Yii configuration

#### Library loading
The classes in the installed Composer packages can be autoloaded using the Composer autoloader. Make sure the entry script of your application contains the following lines to install the Composer autoloader:
```php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
```
In the same file, register a new dependency :
```php
\Yii::$container->setSingleton("Ajax\php\yii\JsUtils",["bootstrap"=>new Ajax\Semantic()]);
```
#### Injection of the service
The **JsUtils** singleton can then be injected into controllers

```php
namespace app\controllers;

use yii\web\Controller;
use Ajax\php\yii\JsUtils;

class SiteController extends Controller{
	protected $jquery;

	public function __construct($id, $module,JsUtils $js){
		parent::__construct($id, $module);
		$this->jquery=$js;
	}
}
```

### <img src="http://angular.kobject.net/git/images/symfony.png" width="30"> Symfony configuration

#### Library loading
If you do not use the Composer autoloader file, you can also load phpMv-UI with [Ps4ClassLoader](http://symfony.com/doc/current/components/class_loader/psr4_class_loader.html) :

```php
use Symfony\Component\ClassLoader\Psr4ClassLoader;

require __DIR__.'/lib/ClassLoader/Psr4ClassLoader.php';

$loader = new Psr4ClassLoader();
$loader->addPrefix('Ajax\\', __DIR__.'/lib/phpmv/php-mv-ui/Ajax');
$loader->register();
```


#### Symfony 4

Create a service inheriting from `JquerySemantic`
```php
namespace App\Services\semantic;

use Ajax\php\symfony\JquerySemantic;

class SemanticGui extends JquerySemantic{
}
```
Check that autowiring is activated in **config/services.yml**:
```yml
services:
    # default configuration for services in *this* file
    _defaults:
        autowire: true      # Automatically injects dependencies in your services.
```
You can then use dependency injection on properties, constructors or setters:

```php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\semantic\SemanticGui;

BarController extends AbstractController{
	/**
	 * @var SemanticGui
	 */
	protected $gui;

    public function loadViewWithAjaxButtonAction(){
    	$bt=$this->gui->semantic()->htmlButton('button1','a button');
    	$bt->getOnClick("/url",'#responseElement');
    	return $this->gui->renderView("barView.html.twig");
    }
}
```
#### Symfony 3
##### Injection of the service
Create 2 services in the **app/config/services.yml** file :
  * The first for the JsUtils instance
  * The second for the controller
  
```yml
parameters:
    jquery.params:
        semantic: true
services:
    jquery:
        class: Ajax\php\symfony\JsUtils 
        arguments: [%jquery.params%,'@router']
        scope: request
    app.default_controller:
        class: AppBundle\Controller\DefaultController 
        arguments: ['@service_container','@jquery']
```
It is then possible to inject the Symfony container and the JsUtils service in the controller constructor :

```php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ajax\php\symfony\JsUtils;
use AppBundle\AppBundle;

/**
 * @Route(service="app.default_controller")
 */
class DefaultController extends Controller{
	/**
	 * @var Ajax\php\symfony\JsUtils
	 */
	protected $jquery;

	public function __construct(ContainerInterface $container,JsUtils $js){
		$this->container=$container;
		$this->jquery= $js;
	}
}
```
### <img src="http://angular.kobject.net/git/images/cakephp.png" width="30"> CakePhp configuration

#### Component creation
Copy the file **JsUtilsComponent.php** located in **vendor/phpmv/php-mv-ui/Ajax/php/cakephp** to the **src/controller/component** folder of your project

#### Component loading in controllers
Add the **JsUtils** component loading in the initialize method of the base controller **AppController**, located in **src/controller/appController.php**
```php
    public function initialize(){
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('JsUtils',["semantic"=>true]);
    }
```
#### Usage

the jquery object in controller will be accessible on
`$this->JsUtils->jquery`

## Code completion in IDE

With most IDEs (such as Eclipse or phpStorm), to get code completion on the `$jquery` instance, you must add the following property in the controller documentation:
```php
/**
  * @property Ajax\JsUtils $jquery
  */
class MyController{
}
```
