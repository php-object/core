#!/usr/bin/env sh

set -eu

/app/vendor/bin/phpunit /app/tests/phpunit --configuration=/app/phpunit.xml ${PHPUNIT_PARAMETERS}
