#!/bin/bash

if [ ! -f /.hiredis ]; then
    /hiredis.sh
fi

/etc/init.d/memcached start

/etc/init.d/redis-server start

php5enmod mcrypt

# source /etc/apache2/envvars

# tail -F /var/log/apache2/* &

apache2ctl start

# exec apache2 -D FOREGROUND

exec /usr/sbin/sshd -D
