[![codecov](https://codecov.io/gh/QoboLtd/cakephp-utils/branch/master/graph/badge.svg)](https://codecov.io/gh/QoboLtd/cakephp-utils)
[![Build Status](https://travis-ci.org/QoboLtd/cakephp-utils.svg?branch=master)](https://travis-ci.org/QoboLtd/cakephp-utils)
[![Latest Stable Version](https://poser.pugx.org/qobo/cakephp-utils/v/stable)](https://packagist.org/packages/qobo/cakephp-csv-migrations)
[![Total Downloads](https://poser.pugx.org/qobo/cakephp-utils/downloads)](https://packagist.org/packages/qobo/cakephp-csv-migrations)
[![Latest Unstable Version](https://poser.pugx.org/qobo/cakephp-utils/v/unstable)](https://packagist.org/packages/qobo/cakephp-csv-migrations)
[![License](https://poser.pugx.org/qobo/cakephp-utils/license)](https://packagist.org/packages/qobo/cakephp-csv-migrations)

CakePHP3.x Qobo Utils Plugin
=======================

Qobo Utils plugin is a swiss knife collection of tools used on daily basis in [Qobo Ltd.](https://www.qobo.biz).

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
-----

* PathFinder for CSV work.
* Parser for CSV files.
* ...And many others that will land here eventually...
