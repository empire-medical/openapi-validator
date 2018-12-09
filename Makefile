build:
	php composer.phar install

test:
	 ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests

cs-fix:
	./vendor/bin/php-cs-fixer fix src
	./vendor/bin/php-cs-fixer fix tests

analyse:
	./vendor/bin/phpstan analyse src  --level=7
