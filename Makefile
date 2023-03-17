up:
	docker-compose run fpm php ./composer.phar install && docker-compose up

down:
	docker-compose down

test_run:
	docker-compose exec fpm vendor/bin/phpunit

rebuild:
	docker-compose up -d --build
