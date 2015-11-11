#FROM daocloud.io/ubuntu:vivid
FROM daocloud.io/php:5.6-apache

MAINTAINER Alvin Jin <jin@aliuda.cn>

# change apt source
ADD sources.list /etc/apt/sources.list

# Install packages
RUN apt-get update && apt-get -y install vim net-tools apt-utils dialog

# Install Runtime deps
RUN apt-get -y install perl ca-certificates curl libpcre3 librecode0 libsqlite3-0 libxml2 zip unzip autoconf file g++ gcc libc-dev make pkg-config re2c memcached redis-server mcrypt libmcrypt-dev libz-dev git wget subversion php5-common php5-dev \

# Install Apache & PHP5 packages
    && docker-php-ext-install mysql apcu curl redis gd mcrypt mbstring memcached sqlite pdo_mysql pdo_sqlite

# 用完包管理器后安排打扫卫生可以显著的减少镜像大小
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

    # 安装 Composer，此物是 PHP 用来管理依赖关系的工具
    # Laravel Symfony 等时髦的框架会依赖它
    && curl -sS https://getcomposer.org/installer | php --install-dir=/usr/local/bin --filename=composer

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN mkdir dir -p /app/public && rm -f /var/www/html/* && ln -s /app/public /var/www/html

RUN a2enmod rewrite 
RUN php5enmod mcrypt

# ADD scripts
ADD hiredis.sh /hiredis.sh
ADD hiredis.zip /root/hiredis.zip
ADD phpiredis.zip /root/phpiredis.zip
RUN /hiredis.sh
RUN /etc/init.d/memcached start
RUN /etc/init.d/redis-server start

WORKDIR /app
COPY ./composer.json /app/
COPY ./composer.lock /app/
RUN composer install  --no-autoloader --no-scripts

COPY . /app

RUN composer install \
    && chown -R www-data:www-data /app \
    && chmod -R 0777 /app/storage


