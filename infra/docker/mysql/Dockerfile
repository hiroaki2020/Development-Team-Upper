FROM mysql/mysql-server:8.0
LABEL maintainer="hiroaki"

ENV TZ=Asia/Tokyo

RUN microdnf update && \
  rm -rf /var/cache/yum && \
  microdnf -y install cronie-noanacron logrotate && \
  microdnf clean all && \
  mkdir /var/log/mysql /run/mysql-socket && \
  touch /var/log/mysql/general.log /var/log/mysql/slow-query.log && \
  chown -R mysql:mysql /var/log/mysql /run/mysql-socket && \
  chmod -R u=rwX,g=rX,o= /var/log/mysql && \
  chmod 750 /run/mysql-socket /entrypoint-wrapper.sh /entrypoint.sh /change-socket-permission.sh && \
  chmod 644 /etc/my.cnf /etc/logrotate.conf /etc/cron.d/0hourly /etc/cron.d/dailyjobs /etc/cron.allow && \
  chown mysql:mysql /entrypoint-wrapper.sh /entrypoint.sh /change-socket-permission.sh /run /var/log && \
  # Uncomment the line below only if you allow container user (=mysql) to install packages via microdnf command.
  #chown mysql:mysql /var/cache/yum /var/cache/yum/lock && \
  groupadd --system --gid 555 dtuj && \
  useradd --system -g dtuj --groups mysql --no-create-home --home-dir /nonexistent --comment dtuj --shell /bin/false --uid 555 dtuj && \
  setcap cap_setuid,cap_setgid,cap_dac_override+ep /usr/sbin/crond && \
  chmod 000 /usr/bin/crontab /usr/bin/cronnext /var/spool/cron

COPY ./infra/docker/mysql/my.cnf /etc/my.cnf
COPY ./infra/docker/mysql/docker-entrypoint-wrapper.sh /entrypoint-wrapper.sh
COPY ./infra/docker/mysql/logrotate.conf /etc/logrotate.conf
COPY ./infra/docker/mysql/0hourly /etc/cron.d/0hourly
COPY ./infra/docker/mysql/dailyjobs /etc/cron.d/dailyjobs
COPY ./infra/docker/mysql/cron.allow /etc/cron.allow
COPY ./infra/docker/mysql/change-socket-permission.sh /change-socket-permission.sh

ENTRYPOINT ["/entrypoint-wrapper.sh"]
# You can replace "mysqld" below in the CMD instruction with "mysqld" command options or simply add them after "mysqld".
CMD ["mysqld"]

USER mysql