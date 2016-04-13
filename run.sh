#!/bin/bash

if [ ! -f /.hiredis ]; then
    /hiredis.sh
fi

/etc/init.d/memcached start

/etc/init.d/redis-server start

php5enmod mcrypt

source /etc/apache2/envvars

# tail -F /var/log/apache2/* &

/etc/init.d/apache2 start

# exec apache2 -D FOREGROUND


