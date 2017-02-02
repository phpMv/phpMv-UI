![php-mv-UI](http://angular.kobject.net/git/phalconist/php-mv-ui-.png "phpMv-UI")

**A JQuery and UI library** (JQuery UI, Twitter Bootstrap, Semantic-UI) for php and php MVC frameworks

[phpMv-UI website](http://phpmv-ui.kobject.net/)

[![Build Status](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/badges/build.png?b=master)](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/build-status/master) [![Latest Stable Version](https://poser.pugx.org/phpmv/php-mv-ui/v/stable)](https://packagist.org/packages/phpmv/php-mv-ui) [![Total Downloads](https://poser.pugx.org/phpmv/php-mv-ui/downloads)](https://packagist.org/packages/phpmv/php-mv-ui) [![License](https://poser.pugx.org/phpmv/php-mv-ui/license)](https://packagist.org/packages/phpmv/php-mv-ui)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/?branch=master)
[![Documentation](https://codedocs.xyz/phpMv/phpMv-UI.svg)](https://codedocs.xyz/phpMv/phpMv-UI/)
<a href="http://phalconist.com/phpMv/phpMv-UI" target="_blank">
![phpMv-UI toolkit](http://phalconist.com/phpMv/phpMv-UI/default.svg)
</a>


##What's phpMv-UI ?
phpMv-UI is a visual components library for php : a php wrapper for jQuery and UI components (jQuery, Twitter Bootstrap, Semantic-UI).

Using the dependency injection, the jQuery object can be injected into **php framework container**, allowing for the generation of jQuery scripts in controllers, respecting the MVC design pattern.

##Requirements/Dependencies

* PHP >= 5.3.9
* JQuery >= 2.0.3
* JQuery UI >= 1.10 [optional]
* Twitter Bootstrap >= 3.3.2 [optional]
* Semantic-UI >= 2.2 [optional]

##Resources
* [API](https://codedocs.xyz/phpMv/phpMv-UI/)
* [Documentation/demo](http://phpmv-ui.kobject.net/)

##I - Installation

### Installing via Composer

Install Composer in a common location or in your project:

```bash
curl -s http://getcomposer.org/installer | php
```
Create the composer.json file in the app directory as follows:

```json
{
    "require": {
        "phpmv/php-mv-ui": "dev-master"
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

##II PHP frameworks configuration
###Library loading
phpMv-UI complies with [PSR-4 recommendations](http://www.php-fig.org/psr/psr-4/) for auto-loading classes.
Whatever the php framework used, with "composer", it is enough to integrate the Composer autoload file.
```php
require_once("vendor/autoload.php");
```
### <img src="http://angular.kobject.net/git/images/phalcon.png" width="30"> Phalcon configuration
####Library loading
Without Composer, It is possible to load the library with the **app/config/loader.php** file :

```php
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(array(
		'Ajax' => __DIR__ . '/../vendor/phpmv/php-mv-ui/Ajax/'
))->register();
```

####Injection of the service

It is necessary to inject the JQuery service at application startup, in the service file **app/config/services.php**, and if necessary instantiate Semantic, Bootstrap or Jquery-ui :
```php
$di->set("jquery",function(){
    $jquery= new Ajax\php\phalcon\JsUtils(array("driver"=>"Jquery"));
    $jquery->semantic(new Ajax\Semantic());//for Semantic UI
    return $jquery;
});
```

####Use in controllers
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
### ![](http://angular.kobject.net/git/images/laravel.png) Laravel configuration

####Library loading
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
####Injection of the service
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

####Library loading
The classes in the installed Composer packages can be autoloaded using the Composer autoloader. Make sure the entry script of your application contains the following lines to install the Composer autoloader:
```php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
```
In the same file, register a new dependency :
```php
\Yii::$container->setSingleton("Ajax\php\yii\JsUtils",["bootstrap"=>new Ajax\Semantic()]);
```
####Injection of the service
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

####Library loading
If you do not use the Composer autoloader file, you can also load phpMv-UI with [Ps4ClassLoader](http://symfony.com/doc/current/components/class_loader/psr4_class_loader.html) :

```php
use Symfony\Component\ClassLoader\Psr4ClassLoader;

require __DIR__.'/lib/ClassLoader/Psr4ClassLoader.php';

$loader = new Psr4ClassLoader();
$loader->addPrefix('Ajax\\', __DIR__.'/lib/phpmv/php-mv-ui/Ajax');
$loader->register();
```
