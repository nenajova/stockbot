#!/bin/sh

set -e

# echo "ServerName localhost" >> /etc/apache2/apache2.conf

echo "Running migrations..."
# php bin/console doctrine:migrations:migrate --no-interaction
# php bin/console app:import-csv data

echo "Starting Apache..."
exec apache2-foreground