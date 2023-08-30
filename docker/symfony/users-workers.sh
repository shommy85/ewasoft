#!/bin/bash

php /var/www/html/bin/console messenger:consume -vv external_messages &

# Wait for any process to exit
wait -n

# Exit with status of process that exited first
exit $?