#!/bin/bash

DBNAME="revo_test"
DBUSER="root"
DBPASS=""
CWD=`pwd`
BUILDDIR=${TRAVIS_BUILD_DIR:=`echo $(dirname $(dirname "$CWD"))`}
BUILDDIR=$BUILDDIR"/"

echo "create database"
if [ "$DBPASS" = "" ]; then
    mysql -h"127.0.0.1" -u$DBUSER -e "drop database if exists "$DBNAME
    mysql -h"127.0.0.1" -u$DBUSER -e "create database "$DBNAME
else
    mysql -h"127.0.0.1" -u$DBUSER -p$DBPASS -e "drop database if exists "$DBNAME
    mysql -h"127.0.0.1" -u$DBUSER -p$DBPASS -e "create database "$DBNAME
fi

echo "create properties.inc.php"
sed "s/mysql_string_username']= '';/mysql_string_username']= '$DBUSER';/g" properties.sample.inc.php | \
sed "s/mysql_string_password']= '';/mysql_string_password']= '$DBPASS';/g" | \
sed "s/config_key'] = 'test';/config_key'] = 'config';/g" > properties.inc.php

echo "build config"

# improve this

CONFIG=`cat revo_install.sample.xml`
CONFIG="${CONFIG/\{\$dbName\}/$DBNAME}"
CONFIG="${CONFIG/\{\$dbUser\}/$DBUSER}"
CONFIG="${CONFIG/\{\$dbPass\}/$DBPASS}"
CONFIG="${CONFIG/\{\$host\}/unit.modx.com}"
CONFIG="${CONFIG/\{\$language\}/en}"
CONFIG="${CONFIG/\{\$managerUser\}/admin}"
CONFIG="${CONFIG/\{\$managerPass\}/admin}"
CONFIG="${CONFIG/\{\$managerEmail\}/admin@modx.com}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"
CONFIG="${CONFIG/\{\$directory\}/$BUILDDIR}"

CONFIG="${CONFIG/\{\$baseUrl\}//}"
CONFIG="${CONFIG/\{\$baseUrl\}//}"
CONFIG="${CONFIG/\{\$baseUrl\}//}"
CONFIG="${CONFIG/\{\$baseUrl\}//}"
CONFIG="${CONFIG/\{\$baseUrl\}//}"
CONFIG="${CONFIG/\{\$baseUrl\}//}"
CONFIG="${CONFIG/\{\$baseUrl\}//}"

echo $CONFIG > ../../setup/config.xml

echo "make core/cache and change permissions"
mkdir ../../core/cache
chmod 777 -R ../../

echo "build core transport"
cp ../build.config.sample.php ../build.config.php
cp ../build.properties.sample.php ../build.properties.php
php ../transport.core.php

echo "Run MODX setup"
php ../../setup/index.php --installmode=new --config=../../setup/config.xml
