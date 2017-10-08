## 系统安装

### 安装配置nginx

1. pkg install nginx
2. vi /usr/local/etc/nginx/nginx.conf
3. echo 'nginx_enable="YES"' >> /etc/rc.conf
4. service nginx onestart


### 安装配置PHP

1. pkg install php71 php71-extensions
2. vi /usr/local/etc/nginx/nginx.conf
3. echo 'php_fpm_enable="YES" >> /etc/rc.conf'
4. service php-fpm onestart


### 安装配置pgsql

1. pkg install postgresql95-server php71-pgsql
2. To set limits, environment stuff like locale and collation and other
	things, you can set up a class in /etc/login.conf before initializing
	the database. Add something similar to this to /etc/login.conf:

		postgres:
		        :lang=zh_CN.UTF-8:
		        :setenv=LC_COLLATE=C:
		        :tc=default:

		and run `cap_mkdb /etc/login.conf'.
		Then add 'postgresql_class="postgres"' to /etc/rc.conf.
3. 安装完成后，会新建一个用户pgsql，家目录为/usr/local/pgsql
   切换到pgsql用户：su root && su postgres。
   创建文件夹: mkdir /usr/local/pgsql/data
   初始化数据库：initdb --encoding=UTF8 -D /usr/local/pgsql/data/
   生成三个数据库：postgres  template0  template1

4. 启动 /usr/local/etc/rc.d/postgresql start 或
		pg_ctl -D /usr/local/pgsql/data/ -l logfile start
	To run PostgreSQL at startup, add
	'postgresql_enable="YES"' to /etc/rc.conf


### 安装配置composer

1. 安装 pkg install php71-openssl
		curl -sS https://getcomposer.org/installer | php
		mv composer.phar /usr/local/bin/composer
2. 设置中国源
		composer config -g repo.packagist composer https://packagist.phpcomposer.com


### 安装nblog