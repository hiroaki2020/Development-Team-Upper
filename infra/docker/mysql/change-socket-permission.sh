#!/bin/bash

set -e

until [ -f /var/lib/mysql-files/mysql-init-complete ] && mysqladmin -s ping && [ -S /run/mysql-socket/mysql.sock ]
do
  sleep 1
done
exec chmod 660 /run/mysql-socket/mysql.sock
