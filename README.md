# symfony-monolog-bundle-poc

## 1 - Get the Symfony Source Code

```
git clone git@github.com:jprivet-dev/symfony.git
cd symfony
git switch symfony-monolog-bundle-poc
```

> The very first time - init all & create new branch `symfony-monolog-bundle-poc`
> ```
> git remote add upstream git@github.com:symfony/symfony.git
> git remote -v
> origin  https://github.com/jprivet-dev/symfony (fetch)
> origin  https://github.com/jprivet-dev/symfony (push)
> upstream        git@github.com:symfony/symfony.git (fetch)
> upstream        git@github.com:symfony/symfony.git (push)
> ```
> 
> ```
> git switch --create symfony-monolog-bundle-poc
> git push origin symfony-monolog-bundle-poc
> ```

## 2 - Creating a new test app

> Update [Symfony CLI](https://symfony.com/download#step-1-install-symfony-cli)

```
cd .. # Exit the symfony folder
symfony new app --version=7.2
cd app
```

All modifications must be made to the [MonologBundle](https://github.com/symfony/monolog-bundle). Install it:

```
composer require symfony/monolog-bundle
```

## 3 - Use the branch `jprivet-dev:symfony-monolog-bundle-poc` in the new `app` project

- Replace Symfony packages by symbolic links to the ones in the new `app` project :

```shell
cd ../symfony # Exit the new app project and go in symfony folder
php link ../app
```