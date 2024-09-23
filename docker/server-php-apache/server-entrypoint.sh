#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
        set -- apache2-foreground "$@"
fi

a2enmod rewrite
./bin/console doctrine:migrations:migrate --no-interaction
./bin/console app:create-api-user

exec "$@"
