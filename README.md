# Monolog bundle with app for testing

_A dedicated environment to develop and test MonologBundle configuration evolutions._

## Description

This repository provides an automated environment for testing evolutions of the **MonologBundle** configuration. Specifically, it integrates and facilitates development for the [handler-configuration-segmentation](https://github.com/jprivet-dev/monolog-bundle/tree/handler-configuration-segmentation)
branch of the [jprivet-dev/monolog-bundle](https://github.com/jprivet-dev/monolog-bundle) fork within a Symfony application.

## Install

To set up the environment, follow these steps:

* **Clone the repository:**

```shell
git clone git@github.com:jprivet-dev/monolog-bundle-with-app-for-testing.git
cd monolog-bundle-with-app-for-testing
```

* **Run the installation script:**

```shell
make install
```

* **Access the application:**

Once the installation is complete, open your browser and navigate to [https://127.0.0.1:8000/](https://127.0.0.1:8000/).

## Environment structure after installation

You will have the following structure:

```
tree -A -L 1 -F --dirsfirst

./
├── alice/            # Contains the Nelmio/Alice project.
├── app/              # The new Symfony application project for testing.
├── GotenbergBundle/  # Contains the SensioLabs GotenbergBundle project.
├── monolog-bundle/   # Contains the forked MonologBundle project (jprivet-dev's fork).
├── poc-bundle/       # Contains a local PocBundle project (experimental area).
├── symfony/          # Contains the Symfony framework core project.
├── ...
└── README.md
```

The installation process establishes **symbolic links** for seamless integration:

* `app/vendor/sensiolabs` links to the `GotenbergBundle` directory.
* `app/vendor/local` links to the `poc-bundle` directory.
* `app/vendor/symfony` links to the `monolog-bundle` and `symfony` core directories.

These links are managed by the `php link` command and Composer's path repository configuration (see the`make install` target in the `Makefile` for details).

**app/composer.json:**

```json
{
  "...": {},
  "require": {
    "...": {},
    "local/poc-bundle": "@dev",
    "sensiolabs/gotenberg-bundle": "@dev",
    "symfony/monolog-bundle": "@dev"
  },
  "repositories": {
    "monolog-poc-bundle": {
      "type": "path",
      "url": "../monolog-poc-bundle"
    },
    "alice": {
      "type": "path",
      "url": "../alice"
    },
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

## Running Tests

### MonologBundle

```shell
make test@monolog
```

## Troubleshooting

When accessing [https://127.0.0.1:8000/](https://127.0.0.1:8000/), you might notice the message "You are using Symfony 7.3.x-DEV version" instead of "You are using Symfony 7.3.x version". This discrepancy arises from the `php link` command used during installation. While `app/composer.json`
correctly indicates a 7.3.x release, the `php link` script appears to alter the reported version string. The root cause of this behavior is currently unknown and under investigation.

## What problem do we want to solve with MonologBundle?

When generating the default **MonologBundle** configuration using `php bin/console config:dump monolog`  (see output: [default-config/monolog.yaml](config/default-config/monolog.yaml)), all available configuration keys are currently attached to a single **handler prototype**.

```shell
php bin/console config:dump-reference monolog # Dump the default configuration for an extension
php bin/console debug:config monolog          # Dump the current configuration for an extension
```

However, not all keys are applicable to every handler type. This makes it challenging to anticipate and determine which specific keys should be used for a given handler.

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

The goal is to modify **MonologBundle** to introduce a new configuration structure and a  `config:dump-reference` output that improves readability by explicitly associating authorized keys with their respective handler types.

This is an ongoing research and development effort, with no definitive choices made yet. Initial sources of inspiration include the configurations of **SecurityBundle** ([default-config/security.yaml](config/default-config/security.yaml)) and **GotenbergBundle
** ([default-config/sensiolabs_gotenberg.yaml](config/default-config/sensiolabs_gotenberg.yaml)).

The [poc-bundle](poc-bundle) is an area for experimentation, to easily present the possibilities, before applying these choices to https://github.com/jprivet-dev/monolog-bundle.

| Configuration file        | See default config
|---------------------------|---------------------------------------------------
| framework.yaml            | Yes
| monolog.yaml              | Yes (but without authorized keys by type)
| monolog_poc.yaml          | Yes (with authorized keys by type - experimental)
| poc.yaml                  | Yes (experimental)
| security.yaml             | Yes
| sensiolabs_gotenberg.yaml | Yes
| workflow.yaml             | No configuration extensions available

## MonologPocBundle: new structure of the `Configuration.php` file

The idea is to propose a new approach in `Configuration.php` (**MonologPocBundle** inherits the experiments of **PocBundle**).

### Group configuration properties by handler type

* Why?
  * Currently, all 46 handler properties are listed in a single configuration block, making it difficult to discern which property belongs to which handler type:
    * See [monolog.yaml](config/default-config/monolog.yaml).
  * Have a file generated with the `config:dump-reference` command, which is much more readable :
    * See [monolog_poc.yaml](config/default-config/monolog_poc.yaml) (Contains the configuration of 17 of the original 46 handlers).
* How?
  * Have a configuration prototype per handler type.

### Segment the `Configuration.php` file

* Why?
  * Make the configuration of the 46 handlers easier to read.
* How?
  * Have one configuration file per handler.
  * Import handler configurations into the [Configuration.php](monolog-poc-bundle/src/DependencyInjection/Configuration.php) file (e.g., `SymfonyMailerHandlerConfiguration`, using the `addConfiguration()` method).
  * Allow common nodes to be reused and limit duplication (using the `template()` method).

### No longer break the `Configuration.php` read chain

* Why?
  * View the entire node hierarchy at a glance.
* How?
  * Do not retrieve child nodes to enrich in a variable.
  * Provide the ability to enrich a child node directly in the configuration chain (with the `addConfiguration()`, `template()`, or `closure()` methods).
  * If the `Config` component builders are limited, extend them to the bare minimum.

### Reuse part of the MonologBundle configuration

* Why?
  * **MonologBundle** is a very rich bundle and has already covered a good portion of the configuration and validation subtleties of the various handlers.
  * If integrating the original **MonologBundle** configurations proves straightforward, this indicates that:
    * we will be able to rely on common configuration practices and make them easier for developers to understand.
    * we will be able to easily evolve and adapt the configurations.
    * we will be able to save a lot of time restructuring the configuration of the 46 handlers.
* How?
  * Make good use of the enrichment mechanisms with the `NodeBuilder` and `NodeDefinition` classes.

### Extract documentation from `Configuration.php`

* Why?
  * The documentation in the [Configuration.php](monolog-poc-bundle/src/DependencyInjection/Configuration.php) file header is not found in the `yaml` file generated with the `config:dump-reference` command.
* How?
  * Retrieve this documentation and dispatch it to the relevant handlers in the configuration.

## Legacy VS New syntax for monolog handlers

This document clarifies the valid and invalid ways to configure Monolog handlers in the bundle, distinguishing between the legacy `type` key and the new `type_xxx` prefixed keys.

### Reminder

**Legacy Syntax:**

```yaml
monolog:
  handlers:
    name:
      type: xxx # This defines the handler type directly
      # ... other handler options ...
```

**New Syntax:**

```yaml
monolog:
  handlers:
    name:
      type_xxx: # The key itself defines the handler type
      # ... handler-specific options ...
```

### Case 1 (invalid): no type definition

This scenario occurs when a handler is defined without specifying its type, either through the `type` key or a `type_xxx` prefixed key.

**Configuration Example:**

```yaml
monolog:
  handlers:
    my_handler: ~ # Or an empty array: my_handler: []
```

**Expected Behavior:**

Invalid. A handler *must* have a type defined to be processed.

**Actual Error:**

```
Invalid configuration for path "monolog.handlers.my_handler": A handler must have a "type" or a "type_NAME" key defined.
```

-----

### Case 2 (valid): legacy `type` syntax

This is the traditional way to define a handler type. It remains fully supported.

**Configuration Example:**

```yaml
monolog:
  handlers:
    my_handler:
      type: stream
      # ... other stream handler options (e.g., path, level) ...
```

**Expected Behavior:**

Valid. This configuration is correctly parsed, and the handler's type is set to `stream`.

**Actual Result:**

No error. The configuration is successfully processed.

### Case 3 (valid): new `type_xxx` syntax

This is the recommended new way to define a handler. The type is implicitly defined by the key, and the `type` sub-key is automatically set for backward compatibility internally.

**Configuration Example:**

```yaml
monolog:
  handlers:
    my_handler:
      type_stream: { } # Or with options: type_stream: { path: "php://stderr" }
```

**Expected Behavior:**

Valid. The `stream` handler is correctly identified. Internally, the `type` key is auto-filled (e.g., `type: stream`) for compatibility purposes.

**Actual Result:**

No error. The configuration is successfully processed.

**Note:** If you run `config:dump-reference` after processing this configuration, you would see the `type` key auto-filled:

```yaml
monolog:
  handlers:
    my_handler:
      type: stream # This is auto-filled for internal use
      type_stream:
      # ... stream handler options ...
```

### Case 4 (invalid): conflicting type definitions (legacy `type` and new `type_xxx` with same value)

This scenario occurs when both the legacy `type` key and a new `type_xxx` prefixed key are used for the same handler, even if they refer to the same type. The bundle enforces a single source of truth for handler type definition.

**Configuration Example:**

```yaml
monolog:
  handlers:
    my_handler:
      type: stream
      type_stream: { }
```

**Expected Behavior:**

Invalid. Configuring the handler type using both syntaxes leads to an ambiguity, even if the types match. The user must choose one syntax.

**Actual Error:**

```
Invalid configuration for path "monolog.handlers.my_handler": A handler can only have one type defined. You have configured multiple types: type_stream and the legacy "type: stream" key. Please choose only one handler type (either a "type_xxx" prefixed key or the legacy "type" key).
```

### Case 5 (invalid): conflicting type definitions (legacy `type` and new `type_xxx` with different values)

Similar to Case 4, this scenario is when both syntaxes are used, but they define *different* handler types. This explicitly highlights the ambiguity.

**Configuration Example:**

```yaml
monolog:
  handlers:
    my_handler:
      type: rotating_file
      type_stream: { }
```

**Expected Behavior:**

Invalid. The presence of conflicting type definitions (from different sources or with different values) is not allowed. The user must choose one syntax.

**Actual Error:**

```
Invalid configuration for path "monolog.handlers.my_handler": A handler can only have one type defined. You have configured multiple types: type_stream and the legacy "type: rotating_file" key. Please choose only one handler type (either a "type_xxx" prefixed key or the legacy "type" key).
```

## Development Environment Configuration (PhpStorm)

To ensure an optimal development experience and consistent code, please configure PhpStorm by following these steps.

### 1. Open the Project

* Open the **root directory** of this repository (the one containing `app/`, `alice/`, `GotenbergBundle/`, etc.) directly in PhpStorm as a single project.

### 2. Configure Directories (Sources, Tests, Exclusions)

To optimize auto-completion, navigation, and code analysis, it's crucial to configure directory types. In PhpStorm's `File > Settings / Preferences > Directories` section, you'll find these options:

```
./
├── alice/
│   ├── doc/
│   ├── fixtures/
│   ├── profiling/
│   ├── src/             # Resource Root
│   ├── tests/           # Tests (Test Sources Root in the context menu)
│   └── vendor-bin/      # Excluded
├── app/
│   ├── bin/
│   ├── config/
│   ├── public/
│   ├── src/             # Resource Root
│   ├── templates/
│   ├── tests/           # Tests (Test Sources Root in the context menu)
│   ├── var/             # Excluded
│   └── vendor/          # Excluded
├── GotenbergBundle/
│   ├── bin/
│   ├── config/
│   ├── docs/
│   ├── src/             # Resource Root
│   ├── templates/
│   └── tests/           # Tests (Test Sources Root in the context menu)
├── monolog-bundle/      # Resource Root
│   ├── DependencyInjection/
│   ├── Resources/
│   ├── SwiftMailer/
│   └── Tests/
├── monolog-poc-bundle/
│   ├── config/
│   ├── src/             # Resource Root
│   └── tests/           # Tests (Test Sources Root in the context menu)
├── poc-bundle/
│   ├── config/
│   ├── src/             # Resource Root
│   └── tests/           # Tests (Test Sources Root in the context menu)
└── symfony/
    └── src/             # Resource Root
```

#### Understanding Directory Types

* **`Resource Root`**: Mark these folders if they contain code, configuration files, or other assets that PhpStorm needs to index for auto-completion and navigation, but which might not strictly follow PSR-4 naming conventions (e.g., `App\Class` in `src/Class.php`). This helps avoid false-positive
  warnings related to namespace compliance while keeping code browsable.
* **`Tests`** (or `Test Sources Root` in the context menu): These directories contain your test files. Marking them as `Tests` ensures PhpStorm runs relevant inspections and allows you to easily run tests directly from the IDE.
* **`Excluded`**: These directories will be ignored by PhpStorm during indexing, search, and code analysis. This significantly improves IDE performance by focusing only on relevant files. Typically, you'll exclude generated files, caches, logs, and third-party vendor directories.

### 3. PHP Code Style (Formatting)

We follow the Symfony code standard, which is based on PSR-12.

* Go to `File > Settings / Preferences > Editor > Code Style > PHP`.
* Click the **`Set From...`** button.
* Choose **`Symfony2`** from the dropdown list.
* Ensure the `Tabs and Indents` tab is configured with **4 spaces** for `Tab size`, `Indent`, and `Continuation indent`, and that `Use tab character` is **unchecked**.

### 4. Quality Tools (PHP-CS-Fixer)

We use **PHP-CS-Fixer** to maintain code consistency. It's installed as a development dependency via Composer (ensure you've run `composer install` in the `app/` directory).

* **PhpStorm Configuration:**
  * Go to `File > Settings / Preferences > Languages & Frameworks > PHP > Quality Tools`.
  * Expand the **`PHP CS Fixer`** section.
  * Verify that the path to the executable is correctly configured (PhpStorm should detect `vendor/bin/php-cs-fixer` if you chose `Use project Composer autoloader`).
  * Check the **`Enable`** box.
* **Applying Formatting:**
  * To manually reformat a file or selection: `Ctrl + Alt + L` (Windows/Linux) or `Cmd + Option + L` (macOS).
  * To run PHP-CS-Fixer on a file: Right-click the file > `More Actions` (or directly in the context menu) > `Run PHP CS Fixer`.

### 5. PHP Interpreter

Configure your PHP interpreter (Docker, WSL, local):

* Go to `File > Settings / Preferences > Languages & Frameworks > PHP`.
* Under `CLI Interpreter`, click the `...` button and add/select your correct PHP interpreter.

## Resources

* Monolog:
  * https://symfony.com/packages/Monolog%20Bundle
  * https://symfony.com/doc/current/logging.html
  * https://github.com/symfony/recipes/tree/main/symfony/monolog-bundle/3.7
* Sources of inspiration:
  * https://symfony.com/doc/current/security.html
  * https://github.com/sensiolabs/GotenbergBundle
  * https://github.com/symfony/workflow
  * https://github.com/nelmio/alice
* The Bundle System:
  * https://symfony.com/doc/current/bundles.html
  * https://symfony.com/doc/current/bundles/best_practices.html
  * https://symfony.com/doc/current/components/config/definition.html

## Comments, suggestions?

Feel free to make comments/suggestions to me in the [Git issues section](https://github.com/jprivet-dev/monolog-bundle-with-app-for-testing/issues).

## License

This project is released under the [**MIT License**](https://github.com/jprivet-dev/monolog-bundle-with-app-for-testing/blob/main/LICENSE).
