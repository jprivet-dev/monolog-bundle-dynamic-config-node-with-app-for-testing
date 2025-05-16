# symfony-monolog-bundle-poc

## 1 - Get the Symfony Source Code

```shell
git clone git@github.com:jprivet-dev/symfony.git
cd symfony
git switch symfony-monolog-bundle-poc
```

> The very first time - init all & create new branch `symfony-monolog-bundle-poc`
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

## 2 - Automatic tests

```shell
composer update
php ./phpunit src/Symfony/Bridge/Monolog
```

## 3 - Creating a new test `app` project

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

## 4 - Use the branch `jprivet-dev:symfony-monolog-bundle-poc` in the new `app` project

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

## 5 - Manual tests

- Start a [Symfony Local Web Server](https://symfony.com/doc/current/setup/symfony_server.html):

```shell
cd app # Go to the new `app` project (if you haven't already done so)
symfony server:start
```

- And go on https://127.0.0.1:8000/.

## Resources

- https://symfony.com/doc/current/logging.html
- https://symfony.com/packages/Monolog%20Bundle