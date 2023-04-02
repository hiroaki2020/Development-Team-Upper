#!/bin/bash

set -e

crond
tail -Fq /var/log/mysql/general.log /var/log/mysql/slow-query.log &
exec /entrypoint.sh "$@"
