FROM nginx:alpine
COPY public/ /usr/share/nginx/html/public
ADD vhost.conf /etc/nginx/conf.d/default.conf