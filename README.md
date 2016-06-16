![php-mv-UI](http://angular.kobject.net/git/phalconist/php-mv-ui-.png "phpMv-UI")

**A JQuery and UI library** (JQuery UI, Twitter Bootstrap, Semantic-UI) for php and php MVC frameworks

[phpMv-UI website](http://phpmv-ui.kobject.net/)

[![Build Status](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/badges/build.png?b=master)](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/build-status/master) [![Latest Stable Version](https://poser.pugx.org/phpmv/php-mv-ui/v/stable)](https://packagist.org/packages/phpmv/php-mv-ui) [![License](https://poser.pugx.org/phpmv/php-mv-ui/license)](https://packagist.org/packages/phpmv/php-mv-ui)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpMv/phpMv-UI/?branch=master)


##What's phpMv-UI ?
phpMv-UI is a php library for php : a php wrapper for JQuery and UI components (JQuery, Twitter Bootstrap, Semantic-UI).

Using the dependency injection, the jQuery object can be injected into **php framework container**, allowing for the generation of jQuery scripts in controllers, respecting the MVC design pattern.

##Requirements/Dependencies

* PHP >= 5.3.9
* JQuery >= 2.0.3
* JQuery UI >= 1.10 [optional]
* Twitter Bootstrap >= 3.3.2 [optional]

##Resources
* [API](http://api.kobject.net/phpMv-UI/) : in progress
* [Documentation](http://slamwiki.kobject.net/en/slam4/php/phalcon/jquery) : in progress

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
