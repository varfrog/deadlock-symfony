#!/bin/bash

docker-compose up -d

docker exec -it varfrog-deadlock-app composer install
docker exec -it varfrog-deadlock-app bin/console doctrine:migrations:migrate --no-interaction
docker exec -it varfrog-deadlock-app bin/console doctrine:fixtures:load --no-interaction
