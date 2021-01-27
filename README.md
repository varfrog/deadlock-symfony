## deadlock-symfony

A PHP app on Symfony and Doctrine that shows when a database deadlock happens.

## Usage

```
docker-compose build
docker-compose up -d
docker exec -it varfrog-deadlock-app sh
```

In the shell of the container:
```
composer install
bin/console doctrine:migrations:migrate --no-interaction
```
