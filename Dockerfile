FROM daocloud.io/1514582970/pms_docker_php:apache71_swoole_phalcon

MAINTAINER      Dongasai "1514582970@qq.com"

RUN apt update;apt install -y vim
COPY . /var/www/html/
COPY file.ini /usr/local/etc/php/conf.d/
ENV APP_SECRET_KEY="123456"
ENV VIRTUAL_HOST=demo01fileweb.y128.psd1412.com

ENV GCACHE_HOST="192.168.1.1"
ENV GCACHE_PORT="6379"
ENV GCACHE_AUTH=0
ENV GCACHE_PERSISTENT=""
ENV GCACHE_PREFIX="email"
ENV GCACHE_INDEX="1"

ENV MYSQL_HOST="192.168.1.1"
ENV MYSQL_PORT="3306"
ENV MYSQL_DBNAME="email"
ENV MYSQL_PASSWORD="123456"
ENV MYSQL_USERNAME="email"
EXPOSE 80
WORKDIR /var/www/html/
RUN composer install
WORKDIR /var/www/html/app/web/
RUN composer install
WORKDIR /var/www/html/
RUN composer install
RUN chmod -R 777 /var/www/html/upload/
