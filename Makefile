install:
	composer install

database:
	bin/console doctrine:database:drop --connection=catalog --force
	bin/console doctrine:database:create --connection=catalog
	bin/console doctrine:migrations:migrate --no-interaction

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

docker-start:
	docker-compose up -d --force-recreate --remove-orphans

dev: install docker-start
	sudo php -S localhost:666 -t public -d xdebug.remote_enable=1
