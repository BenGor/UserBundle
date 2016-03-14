#BenGorUserBundle
> Fully featured and test covered user Symfony bundle built on top of [BenGorUser][6] library

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5a2ac745-59f8-47b9-806a-6d1f4f9c96a6/mini.png)](https://insight.sensiolabs.com/projects/5a2ac745-59f8-47b9-806a-6d1f4f9c96a6)
[![Build Status](https://travis-ci.org/BenGor/UserBundle.svg?branch=master)](https://travis-ci.org/BenGor/UserBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/BenGor/UserBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/BenGor/UserBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/bengor/user-bundle/downloads)](https://packagist.org/packages/bengor/user-bundle)
[![Latest Stable Version](https://poser.pugx.org/bengor/user-bundle/v/stable.svg)](https://packagist.org/packages/bengor/user-bundle)
[![Latest Unstable Version](https://poser.pugx.org/bengor/user-bundle/v/unstable.svg)](https://packagist.org/packages/bengor/user-bundle)

##Requirements
PHP >= 5.5</br>
Symfony >= 2.8 

##Installation
The easiest way to install this component is using **[Composer][7]**
```bash
$ composer require bengor/user-bundle
```
##Documentation

All the documentation is stored in the `docs` folder.

[Show me the docs!](docs/index.md)

##Tests
This bundle is completely tested by **[PHPSpec][1], SpecBDD framework for PHP**.

In case you want to contribute or just run the tests, use the following command.
```bash
$ vendor/bin/phpspec run -fpretty
```

##Contributing
This bundle follows PHP coding standards, so pull requests need to execute the Fabien Potencier's [PHP-CS-Fixer][5].
Furthermore, if the PR creates some not-PHP file remember that you have to put the license header manually. In
order to simplify we provide a Composer script that wraps all the commands related with this process.
```bash
$ composer run-script cs
```

There is also a policy for contributing to this project. Pull requests must be explained step by step to make the
review process easy in order to accept and merge them. New methods or code improvements must come paired with
[PHPSpec][1] tests.

If you would like to contribute it is a good point to follow Symfony contribution standards, so please read the
[Contributing Code][2] in the project documentation. If you are submitting a pull request, please follow the guidelines
in the [Submitting a Patch][3] section and use the [Pull Request Template][4].

##Credits
This bundle is created by:
>
**@benatespina** - [benatespina@gmail.com](mailto:benatespina@gmail.com)<br>
**@gorkalaucirica** - [gorka.lauzirika@gmail.com](mailto:gorka.lauzirika@gmail.com)

##Licensing Options
[![License](https://poser.pugx.org/bengor/user-bundle/license.svg)](https://github.com/BenGor/UserBundle/blob/master/LICENSE)

[1]: http://www.phpspec.net/
[2]: http://symfony.com/doc/current/contributing/code/index.html
[3]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[4]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
[5]: http://cs.sensiolabs.org/
[6]: https://github.com/BenGor/User
[7]: http://getcomposer.org
