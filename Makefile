NODE_VERSION = 18.12.0
NODE_RUN = docker run --rm -v "$(PWD)":/usr/src/app -w /usr/src/app -it node:$(NODE_VERSION)-alpine
DOCKER_COMPOSE = docker compose
PHP_EXEC = docker compose exec php
SYMFONY_CONSOLE = $(PHP_EXEC) bin/console
COMPOSER = $(PHP_EXEC) composer

## —— Docker‍️ ————————————————————————————————————————————————————————————
up:
	XDEBUG_MODE=coverage $(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

logs:
	$(DOCKER_COMPOSE) logs -f

docker-build:
	$(DOCKER_COMPOSE) build --pull --no-cache

fix-permission:
	$(PHP_EXEC) chown -R $(shell id -u):$(shell id -g) .

## —— Php ———————————————————————————————————————————————————————————
php:
	$(PHP_EXEC) sh

composer-install:
	$(COMPOSER) install

cc:
	$(SYMFONY_CONSOLE) c:c

cs-fixer:
	$(COMPOSER) phpcs

phpstan:
	$(COMPOSER) phpstan

phpunit:
	$(COMPOSER) phpunit

## —— Node ————————————————————————————————————————————————————————————
npm:
	$(NODE_RUN) sh

npm-install:
	$(NODE_RUN) npm install

npm-dev:
	$(NODE_RUN) npm run dev

npm-watch:
	$(NODE_RUN) npm run watch

npm-build:
	$(NODE_RUN) npm run build

## —— Database ————————————————————————————————————————————————————————————
db-diff:
	$(SYMFONY_CONSOLE) make:migration

db-generate:
	$(SYMFONY_CONSOLE) doctrine:migration:generate

db-migrate:
	$(SYMFONY_CONSOLE) doctrine:migration:migrate --no-interaction

db-rollback:
	$(SYMFONY_CONSOLE) doctrine:migration:migrate prev --no-interaction
