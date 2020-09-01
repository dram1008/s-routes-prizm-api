#!/bin/bash

# Установить docker
apt install curl
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Установить docker-compose
curl -L "https://github.com/docker/compose/releases/download/1.25.5/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
usermod -a -G docker $USER
systemctl enable docker # Auto-start on boot
systemctl start docker # Start right now
chown $USER:docker /var/run/docker.sock

# Права устанавливаю
chmod 777 runtime
chmod 777 public_html/assets

# Собрать образы
docker-compose build --build-arg GITHUB_TOKEN=9caf02a919d5411bde83c84bfde200a9e002c231

# Запустить докер
docker-compose up -d

# обновить composer
docker exec -it s-routes-php-fpm composer global require "fxp/composer-asset-plugin:^1.2.0"
docker exec -it s-routes-php-fpm composer update