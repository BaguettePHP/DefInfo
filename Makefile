# Makefile for PHPDefInfo

PHP = php
PHPUNIT = ./vendor/bin/phpunit
COMPOSER = composer

sources = src/definfo.php bin/phpdefinfo

.PHONY:build
build: build/phpdefinfo.phar
build/phpdefinfo.phar: $(sources) box.phar
	$(PHP) box.phar build -c box.json
box.phar:
	$(PHP) -r 'readfile("https://box-project.github.io/box2/installer.php");' | $(PHP)


.PHONY: test
test: $(PHPUNIT)
	$(PHP) $(PHPUNIT)


.PHONY: clobber
clobber: clean
	@rm -rf box.phar vendor


.PHONY: clean
clean:
	@rm build/phpdefinfo.phar
