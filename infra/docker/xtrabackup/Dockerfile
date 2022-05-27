FROM percona/percona-xtrabackup:8.0.28
LABEL maintainer="hiroaki"

RUN microdnf update && \
    microdnf -y install nmap-ncat && \
    microdnf clean all