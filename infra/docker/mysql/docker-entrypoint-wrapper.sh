#!/bin/bash

set -e

crond
tail -Fq /var/log/mysql/general.log /var/log/mysql/slow-query.log &
/change-socket-permission.sh &
exec /entrypoint.sh "$@"
