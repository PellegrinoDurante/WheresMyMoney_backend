FROM php:8.1-fpm

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
  install-php-extensions bcmath curl dom mbstring pdo_mysql pdo_pgsql zip @composer

# Install git and nginx
RUN apt-get update && apt-get install -y git nginx

# Install pdf2json
RUN git clone https://github.com/PellegrinoDurante/pdf2json.git && cd pdf2json \
   && ./configure && make && make install

## Install Google Chrome for Laravel Dusk
RUN apt-get install wget -y \
  && cd /tmp && wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb \
  && apt install -y ./google-chrome-stable_current_amd64.deb

# Install app
ADD https://api.github.com/repos/PellegrinoDurante/WheresMyMoney_backend/git/refs/heads/master /tmp/version.json
RUN git clone https://github.com/PellegrinoDurante/WheresMyMoney_backend.git /srv/wmm-be/
RUN chown -R www-data:www-data /srv/wmm-be
RUN cd /srv/wmm-be/ && composer install
COPY --chown=www-data:www-data .env.prod /srv/wmm-be/.env
COPY --chown=www-data:www-data client_secret.json /srv/wmm-be/storage/app/
RUN cd /srv/wmm-be/ && php artisan key:generate && php artisan migrate -n

# Copy nginx configuration and entrypoint script
COPY wmm_be.conf /etc/nginx/sites-enabled/
COPY entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh

EXPOSE 80 443

WORKDIR /srv/wmm-be
ENTRYPOINT ["/etc/entrypoint.sh"]
