FROM php:7.4-cli
RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp

CMD [ "php", "./main.php" ]