FROM php:8.2-cli

# Atualiza os repositórios e instala dependências do sistema, a """libpq-dev""" é necessária para o PHP conversar com o postgree
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql # Instala as extensões de banco de dados do PHP

# Avisa qual é a pasta de trabalho dentro do servidor
WORKDIR /var/www

# Copia todos os arquivos do Composer para o container
COPY composer.json composer.lock* ./

# Baixa e instala o Composer e as dependências do projeto
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php composer.phar install \
    && rm composer-setup.php

COPY . .