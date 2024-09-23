#!/bin/bash

vendor/bin/php-cs-fixer fix --using-cache=no --config=.php-cs-fixer.conf.php src/
vendor/bin/phpstan analyze --configuration=phpstan.neon src/
