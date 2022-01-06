#!/bin/bash

set -e

if [ -f /var/lib/mysql-files/mysql-init-complete ]; then
  rm /var/lib/mysql-files/mysql-init-complete
fi
crond
tail -Fq /var/log/mysql/general.log /var/log/mysql/slow-query.log &
/change-socket-permission.sh &
exec /entrypoint.sh "$@"
