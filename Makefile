docker_compose = docker-compose -f docker-compose.yml
php_container = php

up:
	$(docker_compose) -f docker-compose.yml up --remove-orphans
upd:
	$(docker_compose) -f docker-compose.yml up -d --remove-orphans
down:
	$(docker_compose) down
bashroot:
	$(docker_compose) exec $(php_container) sh
test:
	$(docker_compose) exec $(php_container) sh -c 'vendor/bin/phpunit'
fix:
	$(docker_compose) exec $(php_container) sh -c 'composer fix'
check:
	$(docker_compose) exec $(php_container) sh -c 'composer check'
