all: lint cs unit
travis: lint cs travis-unit
contrib: lint cs unit

init:
	if [ ! -d vendor ]; then composer install; fi;

cs: init
	./vendor/bin/phpcs --standard=PSR2 src/

lint: init
	./vendor/bin/parallel-lint --exclude vendor .

unit: init
	./vendor/bin/phpunit --coverage-text --coverage-html covHtml

travis-unit: init
	./vendor/bin/phpunit --coverage-text --coverage-clover ./build/logs/clover.xml

travis-coverage: init
	if [ -f ./build/logs/clover.xml ]; then wget https://scrutinizer-ci.com/ocular.phar && php ocular.phar code-coverage:upload --format=php-clover ./build/logs/clover.xml; fi
