FROM node:15.10.0-alpine3.13 as node
FROM nginxinc/nginx-unprivileged:1.19.7-alpine
LABEL maintainer="hiroaki"
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

USER root
ENV TZ=Asia/Tokyo

RUN apk update && \
    apk add --update --no-cache --virtual=.build-dependencies g++ && \
    deluser nginx && \
    addgroup --system -g 102 nginx && \
    adduser -S -D -G nginx -H -h /var/cache/nginx -g nginx -s /sbin/nologin -u 102 nginx && \
    find /. -user 101 -print0 | xargs -0 chown -h nginx && \
    addgroup --system -g 555 dtuj && \
    adduser -S -D -G dtuj -H -h /nonexistent -g dtuj -s /bin/false -u 555 dtuj && \
    addgroup nginx dtuj

# node command
COPY --from=node /usr/local/bin /usr/local/bin
# npm command
COPY --from=node /usr/local/lib /usr/local/lib
# yarn command
COPY --from=node /opt /opt
# nginx config file
COPY ./infra/docker/nginx/*.conf.template /etc/nginx/templates/

USER nginx
WORKDIR /work/backend