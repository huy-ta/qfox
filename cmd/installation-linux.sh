#################################################################
#PLEASE DO NOT TOUCH THIS FILE UNLESS YOU KNOW WHAT YOU'RE DOING#
#################################################################

#!/bin/bash

#WORKDIR: Project Root

cd backend

#WORKDIR: backend

[ ! -d "./vendor" ] && docker container run --rm -v $(pwd):/app composer install
sudo chown -R $USER:$USER .
cp .env.example .env

cd ../frontend

#WORKDIR: frontend

[ ! -d "./node_modules" ] && docker container run --rm -v $(pwd):/app node:11-alpine sh -c "cd /app && npm install"

cd ..

#WORKDIR: Project Root

docker-compose up --build -d

docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan optimize

docker-compose down
