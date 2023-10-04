Docker & docker-compose
Manual module work wiki
.env for docker cp .env-example .env

inside docker directory from terminal run:

```
docker-compose build
docker-compose ps
docker-compose up -d
```


Check http://127.0.0.1:8050/api/documentation
If you need artisan use docker-compose exec php-fpm bash

Installation from script.

Running
Just exec

```
sudo sh install.sh
```

run:

```
make fpm-php
php artisan db:seed
```

it will create user with credentials:
```
email: admin@admin.com
pass: admin
```





