#!/bin/bash

if [ ! -f /.hiredis ]; then
    /hiredis.sh
fi

php5enmod mcrypt
/etc/init.d/memcached start
/etc/init.d/redis-server start
apache2ctl start


exec /usr/sbin/sshd -D
