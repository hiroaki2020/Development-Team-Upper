FROM percona/percona-xtrabackup:8.0.28
LABEL maintainer="hiroaki"

RUN microdnf -y update && \
    rm -rf /var/cache/yum && \
    microdnf -y install nmap-ncat shadow-utils && \
    groupadd --system -g 27 mysql && \
    useradd --system -s /usr/bin/false -g mysql -u 27 -c mysql --no-create-home --home-dir /nonexistent mysql && \
    microdnf -y remove shadow-utils && \
    microdnf clean all

USER mysql