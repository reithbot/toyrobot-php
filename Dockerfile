FROM php:7.1-cli-alpine
MAINTAINER Douglas Reith <douglas@reith.com.au>
COPY --from=composer /usr/bin/composer /usr/bin/composer
ADD ./ /toyrobot
WORKDIR /toyrobot
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN /usr/bin/composer install --prefer-dist --optimize-autoloader --no-interaction
CMD ["php", "toyrobot"]
