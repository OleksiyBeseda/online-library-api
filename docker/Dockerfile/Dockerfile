FROM php:8.3-cli

# Устанавливаем необходимые утилиты
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/html

# Копируем весь проект
COPY . /var/www/html

# Устанавливаем зависимости через Composer
RUN composer install --no-interaction

# Делаем bin/console исполняемым
RUN chmod +x bin/console

CMD ["php", "-a"]
