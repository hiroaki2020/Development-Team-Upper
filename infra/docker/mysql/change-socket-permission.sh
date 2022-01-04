#!/bin/bash

set -e

until mysqladmin -s ping
do
  sleep 1
done
while mysqladmin -s ping
do
  sleep 1
done
until mysqladmin -s ping && [ -S /run/mysql-socket/mysql.sock ]
do
  sleep 1
done
exec chmod 660 /run/mysql-socket/mysql.sock
