# Multi-Stage Build File the Universal Animal API


# set up nginx build container
FROM alpine:latest AS nginx
RUN apk add gcc g++ git curl make linux-headers tar gzip

# download pcre library
WORKDIR /src/pcre
ARG PCRE_VER="8.44"
RUN curl -L -O "https://cfhcable.dl.sourceforge.net/project/pcre/pcre/$PCRE_VER/pcre-$PCRE_VER.tar.gz"
RUN tar xzf "/src/pcre/pcre-$PCRE_VER.tar.gz"

# download nginx source
WORKDIR /src/nginx
ARG NGINX_VER="1.18.0"
RUN curl -L -O "http://nginx.org/download/nginx-$NGINX_VER.tar.gz"
RUN tar xzf "nginx-$NGINX_VER.tar.gz"

# configure and build nginx
WORKDIR /src/nginx/nginx-"$NGINX_VER"
RUN ./configure --prefix=/usr/share/nginx \
	--sbin-path=/usr/sbin/nginx \
	--conf-path=/etc/nginx/nginx.conf \
	--error-log-path=/var/log/nginx/error.log \
	--http-log-path=/var/log/nginx/access.log \
	--pid-path=/run/nginx.pid \
	--lock-path=/run/lock/subsys/nginx \
	--http-client-body-temp-path=/tmp/nginx/client \
	--http-proxy-temp-path=/tmp/nginx/proxy \
	--user=www-data \
	--group=www-data \
	--with-threads \
	--with-file-aio \
	--with-pcre="/src/pcre/pcre-$PCRE_VER" \
	--with-pcre-jit \
	--with-http_addition_module \
	--without-http_uwsgi_module \
	--without-http_scgi_module \
	--without-http_gzip_module \
	--without-select_module \
	--without-poll_module \
	--without-mail_pop3_module \
	--without-mail_imap_module \
	--without-mail_smtp_module \
	--with-cc-opt="-Wl,--gc-sections -static -static-libgcc -O2 -ffunction-sections -fdata-sections -fPIE -fstack-protector-all -D_FORTIFY_SOURCE=2 -Wformat -Werror=format-security"
ARG CORE_COUNT="1"
RUN make -j"$CORE_COUNT"
RUN make install


# set up the final container
FROM alpine:latest
EXPOSE 80
WORKDIR /var/www/html

# install php and supervisor
RUN apk add supervisor php7 php7-fpm

# configure php and php-fpm
COPY configs/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY configs/php.ini /etc/php7/conf.d/custom.ini

# setup nginx folders and files
RUN adduser www-data -D -H
RUN mkdir -p /tmp/nginx/{client,proxy} && chown -R www-data:www-data /tmp/nginx/
RUN mkdir -p /var/log/nginx && chown -R www-data:www-data /var/log/nginx
RUN mkdir -p /usr/share/nginx/fastcgi_temp && chown -R www-data:www-data /usr/share/nginx/fastcgi_temp
RUN chown -R www-data:www-data /var/www/html
RUN touch /run/nginx.pid && chown www-data:www-data /run/nginx.pid
RUN mkdir -p /etc/nginx 

# add nginx binaries and confs
COPY --from=nginx /usr/sbin/nginx /usr/sbin/nginx
COPY configs/nginx.conf /etc/nginx/nginx.conf
COPY configs/mime.types /etc/nginx/mime.types
COPY configs/fastcgi.conf /etc/nginx/fastcgi.conf
COPY configs/fastcgi-php.conf /etc/nginx/fastcgi-php.conf

# cats
COPY animals/cats ./cats
COPY index.php ./cats/index.php
# possums
COPY animals/possums ./possums
COPY index.php ./possums/index.php
# raccoons
COPY animals/raccoons ./raccoons
COPY index.php ./raccoons/index.php

# homepage
COPY homepage.php ./index.php

# add supervisord conf
COPY configs/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
