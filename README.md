CakePHP3.x Qobo Utils Plugin
=======================

[![Build Status](https://travis-ci.org/QoboLtd/cakephp-utils.svg?branch=master)](https://travis-ci.org/QoboLtd/cakephp-utils)
[![Latest Stable Version](https://poser.pugx.org/qobo/cakephp-utils/v/stable)](https://packagist.org/packages/qobo/cakephp-utils)
[![Total Downloads](https://poser.pugx.org/qobo/cakephp-utils/downloads)](https://packagist.org/packages/qobo/cakephp-utils)
[![Latest Unstable Version](https://poser.pugx.org/qobo/cakephp-utils/v/unstable)](https://packagist.org/packages/qobo/cakephp-utils)
[![License](https://poser.pugx.org/qobo/cakephp-utils/license)](https://packagist.org/packages/qobo/cakephp-utils)
[![codecov](https://codecov.io/gh/QoboLtd/cakephp-utils/branch/master/graph/badge.svg)](https://codecov.io/gh/QoboLtd/cakephp-utils)
[![BCH compliance](https://bettercodehub.com/edge/badge/QoboLtd/cakephp-utils?branch=master)](https://bettercodehub.com/)

A variety of utilities that are common and useful for several of our plugins and application.

This plugin is developed by [Qobo](https://www.qobo.biz) for [Qobrix](https://qobrix.com).  It can be used as standalone CakePHP plugin, or as part of the [project-template-cakephp](https://github.com/QoboLtd/project-template-cakephp) installation.

Usage
-----

Install the pugin with composer:

```
composer require qobo/cakephp-utils
```

Load the plugin:

```
bin/cake plugin load Qobo/Utils --bootstrap
```

Check that the plugin is loaded:

```
bin/cake plugin loaded
```

The above should output the list of loaded plugins, with `Qobo/Utils` being
one of them.

Functionality
-------------

## AdminLTE

One of the primary objectives of this plugin is to simplify the loading
and configuration of the [AdminLTE](https://github.com/maiconpinto/cakephp-adminlte-theme)
theme CakePHP plugin.  Here is what you need to set it up.

Load the AdminLTE plugin:

```bash
bin/cake plugin load AdminLTE --routes --bootstrap
```

Load AdminLTE plugin configuration at the bottom of `config/bootstrap.php`:

```php
Configure::load('admin_lte', 'default');
```

Here is an example configuration you can stick into `config/admin_lte.php`:

```php
<?php
return [
    'Theme' => [
        'folder' => ROOT,
        'title' => 'My App',
        'logo' => [
            // This will be displayed when main menu is collapsed.
            // You can use an <img> tag in here or anything else you want.
            'mini' => 'A',
            // This will be displayed when main menu is expanded.
            // You can use an <img> tag in here or anything else you want.
            'large' => 'My App',
        ],
        'login' => [
            'show_remember' => true,
            'show_register' => false,
            'show_social' => false,
        ],
    ],
];
```

Load AdminLTE theme in `beforeRender()` method of `src/Controller/AppController.php`:

```php
// At the top of the file, together with other use statements:
use Cake\Core\Configure;

public function beforeRender(Event $event)
{
    $this->viewBuilder()->theme('AdminLTE');
    $this->set('theme', Configure::read('Theme'));
    // $this->set('user', $this->Auth->user());
    $this->set('user', []);
}
```

Load AdminLTE Form Helper in `initialize()` method of `src/View/AppView.php`:

```php
public function initialize()
{
    $this->loadHelper('Form', ['className' => 'AdminLTE.Form']);
}
```

For more information on initializing and configuring the AdminLTE theme,
see [plugin documentation](https://github.com/maiconpinto/cakephp-adminlte-theme)

