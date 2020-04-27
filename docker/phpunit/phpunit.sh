#!/usr/bin/env sh

set -eu

/app/vendor/bin/phpunit /app/tests/phpunit $@
