install:
	composer install

test:
	vendor/bin/phpunit

coverage:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text

infection:
	XDEBUG_MODE=coverage vendor/bin/infection

linter: stan cs

stan:
	vendor/bin/phpstan analyse

cs: ## Run coding style analysis
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run

cs-fix:
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v

