DEBUG ?= false

.PHONY: build
build:
	docker-compose build --pull
	docker rmi debian:buster-slim
	docker rmi composer:latest

.PHONY: update
update: build
	docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: install
install: build
	docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: onboarder-release
onboarder-release:
	@docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php bin/console akeneo:patoche:onboarder-release $(filter-out $@,$(MAKECMDGOALS))
%:
	@:

.PHONY: dump-workflow
dump-workflow:
	docker-compose run --rm php bin/console workflow:dump onboarder_release | dot -Tpng -o tagging_workflow.png
	xdg-open tagging_workflow.png

.PHONY: code-style
code-style:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.phpspec.php

.PHONY: fix-code-style
fix-code-style:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.phpspec.php

.PHONY: static-analysis
static-analysis:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpstan analyse src tests/acceptance -l 7
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpstan analyse tests/integration -l 5

.PHONY: specification
specification:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpspec run

.PHONY: acceptance
acceptance:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat -p acceptance -f pretty -o std --colors

.PHONY: integration
integration:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit

.PHONY: tests
tests: code-style static-analysis specification acceptance integration
