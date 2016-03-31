#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
GITFILE="$DIR/push.log"
ACCEPT="PUSH"

cd $DIR

if [ -f $GITFILE ]; then

    LINE=$(cat "$GITFILE")

    if [ "$LINE" == "$ACCEPT" ]; then
        git pull
        rm -rf var/sessions/*
        rm var/SymfonyRequirements.php
        rm bin/symfony_requirements
        rm web/app_dev.php
        rm web/config.php
        composer --no-dev --no-scripts install
        npm install
        gulp
        bin/console --env=prod cache:clear
        echo "" > $GITFILE
    fi
fi
