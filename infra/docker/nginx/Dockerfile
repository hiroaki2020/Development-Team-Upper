FROM node:15.10.0-alpine3.13 as node
FROM nginxinc/nginx-unprivileged:1.19.7-alpine
LABEL maintainer="hiroaki"
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

USER root
ENV TZ=Asia/Tokyo

RUN apk add --update-cache --no-cache --virtual .build-dependencies g++ && \
    mkdir /work && \
    chown nginx:root /work && \
    chmod 500 /work

# node command
COPY --from=node /usr/local/bin /usr/local/bin
# npm command
COPY --from=node /usr/local/lib /usr/local/lib
# yarn command
COPY --from=node /opt /opt
# nginx config file
COPY ./infra/docker/nginx/*.conf.template /etc/nginx/templates/

WORKDIR /work/backend
USER nginx