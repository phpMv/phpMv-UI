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

Install composer in a common location or in your project:

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

##II PHP framework configuration
![](http://angular.kobject.net/git/images/phalcon.png =50x50)

