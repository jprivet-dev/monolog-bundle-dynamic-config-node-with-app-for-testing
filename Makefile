APP_DIR=app
ALICE_DIR=alice
GOTENBERG_BUNDLE_DIR=GotenbergBundle
MONOLOG_BUNDLE_DIR=monolog-bundle
MONOLOG_POC_BUNDLE_DIR=monolog-poc-bundle
POC_BUNDLE_DIR=poc-bundle
SYMFONY_CORE_DIR=symfony
SYMFONY_VERSION=7.3

CONSOLE=cd app && php bin/console

## — 🎵 🚀 MONOLOG BUNDLE WITH APP FOR TESTING MAKEFILE 🚀 🎵 —————————————————

# Print self-documented Makefile:
# $ make
# $ make help

.DEFAULT_GOAL=help
.PHONY: help
help: ## Print self-documented Makefile
	@grep -E '(^[.a-zA-Z_-]+[^:]+:.*##.*?$$)|(^#{2})' Makefile | awk 'BEGIN {FS = "## "}; { \
		split($$1, line, ":"); targets=line[1]; description=$$2; \
		if (targets == "##") { printf "\033[33m%s\n", ""; } \
		else if (targets == "" && description != "") { printf "\033[33m\n%s\n", description; } \
		else if (targets != "" && description != "") { split(targets, parts, " "); target=parts[1]; alias=parts[2]; printf "\033[32m  %-26s \033[34m%-2s \033[0m%s\n", target, alias, description; } \
	}'
	@echo

## — INSTALL / START 🚀 ———————————————————————————————————————————————————————

.PHONY: all
all: install

.PHONY: install
install: ## Start full environment setup
	@echo "\n--- 1. Creating a new test app project in $(APP_DIR)/ ---"
	@symfony check:requirements || { echo "Symfony requirements check failed. Please ensure your environment meets the requirements."; exit 1; }
	@symfony new $(APP_DIR) --version=$(SYMFONY_VERSION) || { echo "Failed to create Symfony app. Is Symfony CLI installed and accessible?"; exit 1; }

	@echo "\n--- 2. Adding base dependencies to $(APP_DIR)/ ---"
	@cd $(APP_DIR) && \
	composer require symfony/http-client symfony/security-bundle symfony/workflow || { echo "Failed to install base dependencies."; exit 1; }

	@echo "\n--- 3. Cloning external repositories ---"
	@git clone git@github.com:symfony/symfony.git --branch $(SYMFONY_VERSION) --depth 1 $(SYMFONY_CORE_DIR) || { echo "Failed to clone Symfony core."; exit 1; }
	@git clone git@github.com:Jean-Beru/GotenbergBundle.git --branch 1.x $(GOTENBERG_BUNDLE_DIR) || { echo "Failed to clone GotenbergBundle."; exit 1; }
	@git clone git@github.com:jprivet-dev/monolog-bundle.git --branch handler-configuration-segmentation $(MONOLOG_BUNDLE_DIR) || { echo "Failed to clone MonologBundle."; exit 1; }
	@git clone git@github.com:nelmio/alice.git $(ALICE_DIR) || { echo "Failed to clone Nelmio/Alice."; exit 1; }

	@echo "\n--- 4. Linking local bundles to $(APP_DIR)/ ---"
	@cd $(APP_DIR) && \
	php ../$(SYMFONY_CORE_DIR)/link . || { echo "Failed to link Symfony core."; exit 1; } && \
	composer config repositories.gotenberg-bundle path ../$(GOTENBERG_BUNDLE_DIR) || { echo "Failed to configure GotenbergBundle repository."; exit 1; } && \
	composer require sensiolabs/gotenberg-bundle:@dev || { echo "Failed to require GotenbergBundle."; exit 1; } && \
	composer config repositories.monolog-bundle path ../$(MONOLOG_BUNDLE_DIR) || { echo "Failed to configure MonologBundle repository."; exit 1; } && \
	composer require symfony/monolog-bundle:@dev || { echo "Failed to require MonologBundle."; exit 1; } && \
	composer config repositories.alice path ../$(ALICE_DIR) || { echo "Failed to configure Nelmio/Alice repository."; exit 1; } && \
	composer require nelmio/alice:@dev || { echo "Failed to require Nelmio/Alice."; exit 1; } && \
	composer config repositories.poc-bundle path ../$(POC_BUNDLE_DIR) || { echo "Failed to configure PocBundle repository."; exit 1; } && \
	composer require local/poc-bundle:@dev || { echo "Failed to require PocBundle."; exit 1; } && \
	composer config repositories.monolog-poc-bundle path ../$(MONOLOG_POC_BUNDLE_DIR) || { echo "Failed to configure MonologPocBundle repository."; exit 1; } && \
	composer require local/monolog-poc-bundle:@dev || { echo "Failed to require MonologPocBundle."; exit 1; }

	@echo "\n--- 5. Installing development tools in $(APP_DIR)/ ---"
	@cd $(APP_DIR) && \
	composer require --dev "phpunit/phpunit:^9.5.10" "symfony/phpunit-bridge:^7.1" || { echo "Failed to install PHPUnit and Bridge."; exit 1; } && \
	composer require --dev symfony/maker-bundle || { echo "Failed to install MakerBundle."; exit 1; } && \
	composer require doctrine/orm || { echo "Failed to install Doctrine ORM."; exit 1; } && \
	composer require --dev symfony/profiler-pack || { echo "Failed to install Profiler Pack."; exit 1; }

	@echo "\n--- Full environment setup complete. ---"
	@echo "You can now start the server with: make start-server"
	@echo "And run tests with: make run-tests"

.PHONY: clean
clean: ## Clean up the entire environment
	rm -rf $(APP_DIR) $(GOTENBERG_BUNDLE_DIR) $(MONOLOG_BUNDLE_DIR) $(MONOLOG_POC_BUNDLE_DIR) $(POC_BUNDLE_DIR) $(SYMFONY_CORE_DIR)

## — APP (SYMFONY) 🎵 —————————————————————————————————————————————————————————

.PHONY: start
start: app ## Run a local web server
	symfony server:start --dir="app" --daemon

.PHONY: stop
stop: app ## Stop the local web server
	symfony server:stop --dir="app"

.PHONY: cc
cc: app ## Cache clear
	$(CONSOLE) cache:clear

default_config_by_bundle: app ## Generate in a yaml file the default config values defined by Symfony for the chosen bundle
	@$(if $(BUNDLE),, $(error BUNDLE argument is required))
	@printf "#\n# [$(BUNDLE)] Generate the default config values defined by Symfony\n#\n"
	$(CONSOLE) config:dump-reference $(BUNDLE) >../config/default-config/$(BUNDLE).yaml

default_config: ## Generate in yaml files the default config values defined by Symfony for framework, monolog, poc, security, sensiolabs_gotenberg
	-make default_config_by_bundle BUNDLE=framework
	-make default_config_by_bundle BUNDLE=monolog
	-make default_config_by_bundle BUNDLE=poc
	-make default_config_by_bundle BUNDLE=monolog_poc
	-make default_config_by_bundle BUNDLE=security
	-make default_config_by_bundle BUNDLE=sensiolabs_gotenberg
	-make default_config_by_bundle BUNDLE=workflow

actual_config_by_bundle: app ## Generate in a yaml file the actual config values used by the app for the chosen bundle
	@$(if $(BUNDLE),, $(error BUNDLE argument is required))
	@printf "#\n# [$(BUNDLE)] Generate the actual config values used by the app\n#\n"
	$(CONSOLE) debug:config $(BUNDLE) --env=test >../config/actual-config/$(BUNDLE).test.yaml
	$(CONSOLE) debug:config $(BUNDLE) --env=dev >../config/actual-config/$(BUNDLE).dev.yaml
	$(CONSOLE) debug:config $(BUNDLE) --env=prod >../config/actual-config/$(BUNDLE).prod.yaml

actual_config: ## Generate in yaml files the actual config values used by the app for framework, monolog, poc, security, sensiolabs_gotenberg
	-make actual_config_by_bundle BUNDLE=framework
	-make actual_config_by_bundle BUNDLE=monolog
	-make actual_config_by_bundle BUNDLE=poc
	-make actual_config_by_bundle BUNDLE=monolog_poc
	-make actual_config_by_bundle BUNDLE=security
	-make actual_config_by_bundle BUNDLE=sensiolabs_gotenberg
	-make actual_config_by_bundle BUNDLE=workflow

## — MONOLOG 📝 ———————————————————————————————————————————————————————————————

default_config@monolog: BUNDLE=monolog
default_config@monolog: default_config_by_bundle ##

actual_config@monolog: BUNDLE=monolog
actual_config@monolog: actual_config_by_bundle ##

monolog_test: app ## Launch all Monolog Bundle tests
	cd app && ./vendor/bin/phpunit vendor/symfony/monolog-bundle/Tests $(ARG)

