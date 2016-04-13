FROM daocloud.io/ubuntu:vivid
MAINTAINER Alvin Jin <jin@aliuda.cn>

# ENV DEBIAN_FRONTEND noninteractive

# change apt source
ADD sources.list /etc/apt/sources.list

# Install packages
RUN apt-get update && apt-get -y install openssh-server vim net-tools apt-utils dialog cron

# Install Runtime deps
RUN apt-get -y install perl ca-certificates curl libpcre3 librecode0 libsqlite3-0 libxml2 zip unzip autoconf file g++ gcc libc-dev make pkg-config re2c memcached redis-server mcrypt

# Install Apache & PHP5 packages
RUN apt-get -y install apache2 mysql-client libapache2-mod-php5 php5-mysql php5-apcu php5-curl php5-redis php5-apcu php5-gd php5-mcrypt php5-memcached php5-sqlite php5-common php5-dev \

# 用完包管理器后安排打扫卫生可以显著的减少镜像大小
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* 

ADD apache_default /etc/apache2/sites-available/000-default.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
RUN echo "ServerSignature off" >> /etc/apache2/apache2.conf

# RUN mkdir dir -p /app/public && rm -f /var/www/html/* && ln -s /app/public /var/www/html
RUN mkdir dir -p /var/www/html

RUN a2enmod rewrite
RUN php5enmod mcrypt

# ADD scripts
ADD hiredis.sh /hiredis.sh
ADD hiredis.zip /root/hiredis.zip
ADD phpiredis.zip /root/phpiredis.zip
ADD run.sh /run.sh

RUN chmod +x /*.sh

EXPOSE 22 80

CMD ["/run.sh"]
