CONSOLE=cd app && php bin/console

DEFAULT_GOAL = help
.PHONY: help
help: ## Print self-documented Makefile
	@grep -E '(^[.a-zA-Z_-]+[^:]+:.*##.*?$$)|(^#{2})' Makefile | awk 'BEGIN {FS = "## "}; { \
		split($$1, line, ":"); targets=line[1]; description=$$2; \
		if (targets == "##") { printf "\033[33m%s\n", ""; } \
		else if (targets == "" && description != "") { printf "\033[33m\n%s\n", description; } \
		else if (targets != "" && description != "") { split(targets, parts, " "); target=parts[1]; alias=parts[2]; printf "\033[32m  %-26s \033[34m%-2s \033[0m%s\n", target, alias, description; } \
	}'
	@echo

start: app ## [app] Run a local web server
	symfony server:start --dir="app" --daemon

stop: app ## [app] Stop the local web server
	symfony server:stop --dir="app"

cc: app ## [app] Cache clear
	$(CONSOLE) cache:clear

default_config_by_bundle: app ## [app] Generate in a yaml file the default config values defined by Symfony for the chosen bundle
	@$(if $(BUNDLE),, $(error BUNDLE argument is required))
	@printf "#\n# [$(BUNDLE)] Generate the default config values defined by Symfony\n#\n"
	$(CONSOLE) config:dump-reference $(BUNDLE) >../config/default-config/$(BUNDLE).yaml

actual_config_by_bundle: app ## [app] Generate in a yaml file the actual config values used by the app for the chosen bundle
	@$(if $(BUNDLE),, $(error BUNDLE argument is required))
	@printf "#\n# [$(BUNDLE)] Generate the actual config values used by the app\n#\n"
	$(CONSOLE) debug:config $(BUNDLE) >../config/actual-config/$(BUNDLE).yaml

generate_default_config: ## [app] Generate in yaml files the default config values defined by Symfony for framework, monolog, poc, security, sensiolabs_gotenberg
	-make default_config_by_bundle BUNDLE=framework
	-make default_config_by_bundle BUNDLE=monolog
	-make default_config_by_bundle BUNDLE=poc
	-make default_config_by_bundle BUNDLE=security
	-make default_config_by_bundle BUNDLE=sensiolabs_gotenberg
	-make default_config_by_bundle BUNDLE=workflow

generate_actual_config: ## [app] Generate in yaml files the actual config values used by the app for framework, monolog, poc, security, sensiolabs_gotenberg
	-make actual_config_by_bundle BUNDLE=framework
	-make actual_config_by_bundle BUNDLE=monolog
	-make actual_config_by_bundle BUNDLE=poc
	-make actual_config_by_bundle BUNDLE=security
	-make actual_config_by_bundle BUNDLE=sensiolabs_gotenberg
	-make actual_config_by_bundle BUNDLE=workflow

