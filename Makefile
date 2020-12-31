install:
	composer install

test:
	vendor/bin/phpunit

coverage:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text --coverage-xml=var/coverage-xml/ --coverage-clover=var/coverage.xml --log-junit=var/coverage-xml/junit.xml --coverage-html=var/coverage/

infection:
	XDEBUG_MODE=coverage vendor/bin/infection --log-verbosity=default --coverage=var/coverage-xml/

linter: stan cs

stan:
	vendor/bin/phpstan analyse

cs: ## Run coding style analysis
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run

cs-fix:
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v
