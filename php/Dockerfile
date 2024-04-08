FROM php:8.2-fpm

RUN useradd -ms /bin/bash appuser

RUN apt-get update && apt-get install libzip-dev libpng-dev libjpeg-dev ca-certificates curl gnupg wget -y

# RUN wget http://memcached.org/latest
# RUN tar -zxvf memcached-1.6.21.tar.gz
# RUN cd memcached-1.6.21
# RUN ./configure make && make test && sudo make install

RUN apt install libsodium-dev

RUN docker-php-ext-install pdo_mysql zip sodium exif gd && docker-php-ext-enable pdo_mysql zip sodium exif

RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ 
# RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ 

RUN docker-php-ext-install -j$(nproc) gd

RUN pecl install -o -f xdebug-3 \
    && docker-php-ext-enable xdebug

# Copy php.ini
COPY ./php.ini /usr/local/etc/php/

# RUN mkdir -p /etc/apt/keyrings

# RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
# RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list

# RUN apt-get update && apt-get install nodejs -y
# RUN npm install npm -g

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN chown -R appuser:appuser /var/www/html

USER appuser

EXPOSE 5173

CMD ["php-fpm"]
