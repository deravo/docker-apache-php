FROM daocloud.io/ubuntu:vivid
MAINTAINER Alvin Jin <jin@aliuda.cn>

ENV DEBIAN_FRONTEND noninteractive

# change apt source
ADD sources.list /etc/apt/sources.list

# Install packages
RUN apt-get update && apt-get -y install openssh-server vim net-tools apt-utils dialog
# RUN mkdir -p /var/run/sshd && sed -i "s/UsePrivilegeSeparation.*/UsePrivilegeSeparation no/g" /etc/ssh/sshd_config && sed -i "s/UsePAM.*/UsePAM no/g" /etc/ssh/sshd_config && sed -i "s/PermitRootLogin.*/PermitRootLogin yes/g" /etc/ssh/sshd_config

# Install Runtime deps
RUN apt-get -y install perl ca-certificates curl libpcre3 librecode0 libsqlite3-0 libxml2 zip unzip autoconf file g++ gcc libc-dev make pkg-config re2c memcached redis-server mcrypt

# For DaoCloud.io Use A MySQL SaaS Instance
# Install MySQL 5.6
# RUN apt-get -yq install mysql-server-5.6 mysql-client-5.6

# Install Apache & PHP5 packages
RUN apt-get -y install git subversion apache2 mysql-client libapache2-mod-php5 php5-mysql php5-apcu php5-curl php5-redis php5-apcu php5-gd php5-mcrypt php5-memcached php5-sqlite php5-common php5-dev \

# 用完包管理器后安排打扫卫生可以显著的减少镜像大小
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

    # 安装 Composer，此物是 PHP 用来管理依赖关系的工具
    # Laravel Symfony 等时髦的框架会依赖它
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN mkdir dir -p /app/public && rm -f /var/www/html/* && ln -s /app/public /var/www/html

RUN a2enmod rewrite
RUN php5enmod mcrypt

# ADD scripts
#ADD hiredis.sh /hiredis.sh
#ADD hiredis.zip /root/hiredis.zip
#ADD phpiredis.zip /root/phpiredis.zip
#RUN /hiredis.sh
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
