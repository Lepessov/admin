# Сборка всего проекта
build:
	sudo docker-compose build

# Поднятие всего проекта
up:
	docker-compose up -d

# Просмотр активных сервисов
ps:
	docker-compose ps

# Положить все сервисы
down:
	docker-compose down

# Рестартануть все сервисы
restart:
	docker-compose restart

# Пересобрать весь проект
rebuild:
	docker-compose down
	docker-compose build
	docker-compose up -d
	sh install_app.sh

# Поднять только сервис nginx
up-nginx:
	docker-compose up -d nginx --build

# Поднять только сервис adata-api
up-php-fpm:
	docker-compose up -d php-fpm --build

# Положить только сервис nginx
down-nginx:
	docker-compose down nginx

# Положить только сервис php-fpm
down-php-fpm:
	docker-compose down php-fpm

# Консоль для php-fpm
php-fpm:
	docker-compose exec php-fpm bash

