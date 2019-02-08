#!/bin/bash

DBNAME=revo_test
DBUSER=root
DBPASS=
MDXHOST=unit.modx.com
MDXLANG=en
MDXUSER=admin
MDXPASS=admin
MDXEMAIL=admin@modx.com

if [[ "$1" != "auto" ]]
then

    echo "Please setup MODX in this prompt."

    read -p "Enter your database [revo_test]: " input
    DBNAME=${input}

    read -p "Enter your database user [root]: " input
    DBUSER=${input}

    read -s -p "Enter your database password []: " input
    DBPASS=${input}

    read -p "Enter your host [unit.modx.com]: " input
    MDXHOST=${input}

    read -p "Enter your language [en]: " input
    MDXLANG=${input}

    read -p "Enter your modx admin userinput [admin]: " input
    MDXUSER=${input}

    read -s -p "Enter your modx admin password [admin]: " input
    MDXPASS=${input}

    read -p "Enter your modx admin email [admin@modx.com]: " input
    MDXEMAIL=${input}

else

    echo "Script started without a prompt. Default values are used."

fi

CWD=`pwd`
BUILDDIR=${TRAVIS_BUILD_DIR:=`echo $(dirname $(dirname "$CWD"))`}
BUILDDIR=$BUILDDIR"/"

echo "create database"
if [ "$DBPASS" = "" ]; then
    mysql -u$DBUSER -e "drop database if exists "$DBNAME
    mysql -u$DBUSER -e "create database "$DBNAME
else
    mysql -u$DBUSER -p$DBPASS -e "drop database if exists "$DBNAME
    mysql -u$DBUSER -p$DBPASS -e "create database "$DBNAME
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
CONFIG="${CONFIG/\{\$host\}/$MDXHOST}"
CONFIG="${CONFIG/\{\$language\}/$MDXLANG}"
CONFIG="${CONFIG/\{\$managerUser\}/$MDXUSER}"
CONFIG="${CONFIG/\{\$managerPass\}/$MDXPASS}"
CONFIG="${CONFIG/\{\$managerEmail\}/$MDXEMAIL}"
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
