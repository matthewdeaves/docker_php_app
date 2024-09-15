#Use Alpine linux based Nginx image
FROM nginx:alpine

#Update sources and upgrade software
RUN apk update --no-cache && \
apk upgrade  --no-cache

#Put the  Nginx default.conf in place
ADD docker/nginx/default.conf /etc/nginx/conf.d