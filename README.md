# symfony-monolog-bundle-poc

## Description

This repository (https://github.com/jprivet-dev/symfony-monolog-bundle-poc) automatically generates the environment needed to test `MonologBundle` evolutions, from https://github.com/jprivet-dev/symfony/tree/symfony-monolog-bundle-poc, in a Symfony application.

After installation, you will have the following structure :

```
tree -A -L 1 -F --dirsfirst
./
├── app/      # New app project for testing
├── symfony/  # Contains MonologBundle evolutions
├── LICENSE
└── README.md
```

## Install

```shell
git clone git@github.com:jprivet-dev/symfony-monolog-bundle-poc.git
cd symfony-monolog-bundle-poc
. install.sh
```

## What does the `install.sh` do?

### 1 - Get the Symfony Source Code

```shell
git clone git@github.com:jprivet-dev/symfony.git --branch symfony-monolog-bundle-poc
cd symfony
```

> The first time, I initiated and created a new `symfony-monolog-bundle-poc` branch:
> 
> ```shell
> git remote add upstream git@github.com:symfony/symfony.git
> git remote -v
> origin  https://github.com/jprivet-dev/symfony (fetch)
> origin  https://github.com/jprivet-dev/symfony (push)
> upstream        git@github.com:symfony/symfony.git (fetch)
> upstream        git@github.com:symfony/symfony.git (push)
> ```
> 
> ```shell
> git switch --create symfony-monolog-bundle-poc
> git push origin symfony-monolog-bundle-poc
> ```

### 2 - Launch automatic tests

```shell
composer update
php ./phpunit src/Symfony/Bridge/Monolog
```

### 3 - Create a new test `app` project

> Update [Symfony CLI](https://symfony.com/download#step-1-install-symfony-cli)

- Check if your system is ready to run Symfony projects:

```shell
symfony check:requirements
```

- And create new Symfony project:

```shell
cd .. # Exit the symfony folder
symfony new app --version=7.2
cd app
```

- All modifications must be made to the [MonologBundle](https://github.com/symfony/monolog-bundle). Install it:

```shell
composer require symfony/monolog-bundle
```

### 4 - Use the `https://github.com/jprivet-dev/symfony/tree/symfony-monolog-bundle-poc` branch in the new `app` project

- Replace Symfony packages by symbolic links to the ones in the new `app` project :

```shell
cd ../symfony # Exit the new app project and go in symfony folder
php link ../app
```

```
...
"symfony/monolog-bridge" has been linked to "/.../symfony/src/Symfony/Bridge/Monolog".
...
```

- Go back in the new `app` project and check symlinks:

```shell
cd ../app # Exit the symfony folder and go in the new app project
ls -la vendor/symfony
```

```
...
monolog-bridge -> ../../../symfony/src/Symfony/Bridge/Monolog/
...
```

- From then on, the `app` application will benefit, among other things, from all the changes made to `symfony/src/Symfony/Bridge/Monolog`.

## How do I perform manual tests?

- Start a [Symfony Local Web Server](https://symfony.com/doc/current/setup/symfony_server.html):

```shell
symfony server:start --dir=app --daemon
```

- And go on https://127.0.0.1:8000/.


- Stop the [Symfony Local Web Server](https://symfony.com/doc/current/setup/symfony_server.html):

```shell
symfony server:stop --dir=app
```

## Resources

- https://symfony.com/doc/current/logging.html
- https://symfony.com/packages/Monolog%20Bundle