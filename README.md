# Monolog bundle dynamic config node with Symfony app for testing

## Description

This repository (https://github.com/jprivet-dev/monolog-bundle-dynamic-config-node-with-app-for-testing) automatically generates the environment needed to test `MonologBundle` evolutions, from https://github.com/jprivet-dev/symfony/tree/monolog-bundle-dynamic-config-node, in a Symfony application.

After installation, you will have the following structure :

```
tree -A -L 1 -F --dirsfirst

./
├── app/              # New app project for testing
├── GotenbergBundle/  # Contains GotenbergBundle project
├── monolog-bundle/   # Contains MonologBundle project
├── symfony/          # Contains symfony project
├── install.sh
├── LICENSE
└── README.md
```

> Symbolic links are created between :
> - `app/vendor/sensiolabs` and `GotenbergBundle` directories
> - `app/vendor/symfony` and `monolog-bundle` and `symfony` directories

## Install

- Clone the project:

```shell
git clone git@github.com:jprivet-dev/monolog-bundle-dynamic-config-node-with-app-for-testing.git
cd monolog-bundle-dynamic-config-node-with-app-for-testing
```

- Run the installation script:

```shell
. install.sh
```

- Go on https://127.0.0.1:8000/ (or https://127.0.0.1:8001/...).

## Run monolog-bundle tests

```shell
cd monolog-bundle
composer update
vendor/bin/simple-phpunit
```

## PocBundle

- Activate `PocBundle`:

```php
// app/config/bundles.php
return [
    // ...
    Local\Bundle\PocBundle\PocBundle::class => ['dev' => true],
];
```

- The `install.sh` script links dependencies of the app project to the local bundle:

```shell
cd app
composer config repositories.poc-bundle path ../poc-bundle
composer require local/poc-bundle:@dev
```

## Troubleshooting

At https://127.0.0.1:8000/ (or https://127.0.0.1:8001/...), you'll see `You are using Symfony 7.2.x-DEV version`, whereas you should see `You are using Symfony 7.2.x version`. According to the `app/composer.json`, it is indeed a `7.2.x` version that is installed.

It's from the `php link` command in the `install.sh` script that the displayed version changes. For the moment, I don't know why!

## Resources

- https://symfony.com/doc/current/logging.html
- https://symfony.com/packages/Monolog%20Bundle
- https://symfony.com/doc/current/security.html
- https://github.com/sensiolabs/GotenbergBundle
- https://symfony.com/doc/current/bundles.html
