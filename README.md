# Monolog bundle dynamic config node with Symfony app for testing

## Description

This repository (https://github.com/jprivet-dev/monolog-bundle-dynamic-config-node-with-app-for-testing) automatically generates the environment needed to test `MonologBundle` evolutions, from https://github.com/jprivet-dev/symfony/tree/monolog-bundle-dynamic-config-node, in a Symfony application.

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

- Activate `PocBundle` in the `app`:

```php
// app/config/bundles.php
return [
    // ...
    Local\Bundle\PocBundle\PocBundle::class => ['dev' => true],
];
```

- Go on https://127.0.0.1:8000/.

After installation, you will have the following structure :

```
tree -A -L 1 -F --dirsfirst

./
├── app/              # New app project for testing
├── GotenbergBundle/  # Contains GotenbergBundle project
├── monolog-bundle/   # Contains MonologBundle project (jprivet-dev fork)
├── poc-bundle/       # Contains local PocBundle project (experimental area)
├── symfony/          # Contains symfony project
├── ...
└── README.md
```

Symbolic links are created between :

- `app/vendor/sensiolabs` and `GotenbergBundle` directory
- `app/vendor/local` and `poc-bundle` directory
- `app/vendor/symfony` and `monolog-bundle` and `symfony` directories

... with the `php link` command and `composer` configuration (see `install.sh`) :

```json
// app/composer.json
{
  "...": {},
  "require": {
    "...": {},
    "local/poc-bundle": "@dev",
    "sensiolabs/gotenberg-bundle": "@dev",
    "symfony/monolog-bundle": "@dev"
  },
  "repositories": {
    "poc-bundle": {
      "type": "path",
      "url": "../poc-bundle"
    },
    "monolog-bundle": {
      "type": "path",
      "url": "../monolog-bundle"
    },
    "gotenberg-bundle": {
      "type": "path",
      "url": "../GotenbergBundle"
    }
  }
}
```

## Run tests

### MonologBundle

```shell
cd monolog-bundle
composer update
vendor/bin/simple-phpunit
```

### PocBundle

```shell
cd poc-bundle
composer update
vendor/bin/simple-phpunit
```

## Troubleshooting

At https://127.0.0.1:8000/, you'll see `You are using Symfony 7.2.x-DEV version`, whereas you should see `You are using Symfony 7.2.x version`. According to the `app/composer.json`, it is indeed a `7.2.x` version that is installed.

It's from the `php link` command in the `install.sh` script that the displayed version changes. For the moment, I don't know why!

## What problem do we want to solve with MonologBundle?

When you generate the default `MonologBundle` configuration, with the command `php bin/console config:dump monolog` (see output [config-dump-monolog.yaml](config-dump/config-dump-monolog.yaml)), all usable keys are attached to a single `handler` prototype.

However, depending on the type of handler, not all keys can be activated. Under these conditions, it is not easy to anticipate and know which key to use depending on the `handler`.

For example, the `max_files` key can only be used for a handler of type `rotating_file`:

```yaml
monolog:
  handlers:
    main:
      type: rotating_file
      path: '%kernel.logs_dir%/%kernel.environment%.log'
      level: debug
      max_files: 10
```

But in the dump, you can see that this `max_files` key is in the middle of all the others:

```yaml
monolog:
  use_microseconds: true
  channels: []
  handlers:
    name:
      # ...
      ident: false
      logopts: 1
      facility: user
      max_files: 0       # <-----
      action_level: WARNING
      activation_strategy: null
      stop_buffering: true
      # ...
```

The aim would be to modify the `MonologBundle`, to propose a new configuration structure and a dump which would make it easier to read, by explicitly attaching the authorised keys according to the `type` chosen.

Everything is being researched, and there is no definite choice yet. The configurations of `SecurityBundle` ([config-dump-security.yaml](config-dump/config-dump-security.yaml)) or GotenbergBundle ([config-dump-gotenberg.yaml](config-dump/config-dump-gotenberg.yaml)) will be the first sources of inspiration.

The [poc-bundle](poc-bundle) is an area for experimentation, to easily present the possibilities, before applying these choices to https://github.com/jprivet-dev/monolog-bundle.


| Configuration file          | See default config                                        
|-----------------------------|----------------------------------------------------------- 
| `framework.yaml`            | Yes                                                       
| `monolog.yaml`              | Yes (but without authorized keys by type)                 
| `poc.yaml`                  | Yes (experimental)                                        
| `security.yaml`             | Yes                                                       
| `sensiolabs_gotenberg.yaml` | Yes                                                       
| `workflow.yaml`             | No extensions with configuration available for "workflow" 

## Resources

- Monolog:
  - https://symfony.com/packages/Monolog%20Bundle
  - https://symfony.com/doc/current/logging.html
  - https://github.com/symfony/recipes/tree/main/symfony/monolog-bundle/3.7
- Sources of inspiration:
  - https://symfony.com/doc/current/security.html
  - https://github.com/sensiolabs/GotenbergBundle
  - https://github.com/symfony/workflow
- The Bundle System:
  - https://symfony.com/doc/current/bundles.html
  - https://symfony.com/doc/current/bundles/best_practices.html
  - https://symfony.com/doc/current/components/config/definition.html
