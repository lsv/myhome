#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
GITFILE="$DIR/var/cache/push.log"
ACCEPT="PUSH"

cd "$DIR"

if [ -f "$GITFILE" ]; then

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
        node_modules/.bin/gulp
        bin/console --env=prod cache:clear
        chmod 777 -R var/cache var/logs
        rm "$GITFILE"
    fi
fi
