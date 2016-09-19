FROM registry.aliyuncs.com/marmot/php7-dev

COPY . /var/www/html/
RUN composer install