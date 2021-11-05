FROM node:15.10.0-alpine3.13 as node
FROM nginxinc/nginx-unprivileged:1.19.7-alpine
LABEL maintainer="hiroaki"
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

USER root
ENV TZ=Asia/Tokyo

RUN apk update && \
  apk add --update --no-cache --virtual=.build-dependencies g++
RUN addgroup --system -g 555 dtuj
RUN adduser -S -D -G dtuj -H -h /nonexistent -g "dtuj" -s /bin/false -u 555 dtuj
RUN addgroup nginx dtuj

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