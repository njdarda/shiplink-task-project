#!/bin/bash

composer install
bin/console lexik:jwt:generate-keypair

bin/console doctrine:database:create
bin/console doctrine:migrations:migrate -n
symfony console doctrine:fixtures:load -n
bin/console app:update-product-database -t

bin/console doctrine:database:create --env test
bin/console doctrine:migrations:migrate -n --env test
symfony console doctrine:fixtures:load -n --env test
