command = ./vendor/bin/sail $(1) $(2)

composer = @docker run --rm --interactive --tty --volume `pwd`:/app composer:2.6.5 install

.PHONY: up
up:
	$(call command, up -d)

.PHONY: up-wait
up-wait:
	$(call command, up -d --wait)

.PHONY: down
down:
	$(call command, down)

.PHONY: migrate
migrate:
	$(call command, artisan, migrate)

.PHONY: install
install:
	$(call composer)

.PHONY: key
key:
	$(call command, artisan, key:generate)

.PHONY: npm-install
npm-install:
	$(call command, npm, install)

.PHONY: vite-build
vite-build:
	$(call command, npm, run build)

.PHONY: test
test:
	$(call command, artisan, test)

.PHONY: stan
stan:
	$(call command, bin, phpstan analyse --memory-limit=2G)

.PHONY: format
format:
	$(call command, bin, php-cs-fixer fix)

.PHONY: lint
lint:
	$(call command, bin, php-cs-fixer fix --dry-run)
