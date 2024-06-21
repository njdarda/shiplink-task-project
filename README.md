# Recruitment task for Shiplink

## Summary
A simple project based on Symfony 5.4 and ApiPlatform.

Task:
[Shiplink - PHP Task.pdf](docs%2FShiplink_-_PHP_Task.pdf)

This application provides an API enabling users to create, cancel and recreate orders.

## Live demo
API docs are available here:
- Swagger docs: http://shiplink.jdarda.pl/api/docs

  You can explore the APIas admin or user by logging in through `/auth` endpoint by using following payloads:
  ```json
  {
    "username": "admin",
    "password": "admin_password"
  }
  ```
  ```json
  {
    "username": "user",
    "password": "user_password"
  }
  ```
  Once you get the token click on `Authorize` button and paste the token with `Bearer` prefix - e.g. `Bearer [TOKEN]`
- ReDoc docs: http://shiplink.jdarda.pl/api/docs?ui=re_doc
- GraphQL: http://shiplink.jdarda.pl/api/graphql/graphql_playground

## Running the application locally

### Prerequisites
The preferred way to run the application is using `docker-compose`.
You need these packages installed on your system:
- docker (version 20 or higher)
- docker-compose (version 1.21 or higher)

Once you have these install run the following command from the project's main directory

```bash
(cd docker/ && docker-compose -f docker-compose.yml up --build)
```

### Managing application

Once the app is app and running you can do the following things:
#### Run installation script (dependencies, database, migrations etc.)
```bash
docker exec -it docker_php-fpm_1 ./init.sh
```

#### Running database migrations
```bash
docker exec -it app_php-fpm_1 bin/console doctrine:migrations:migrate
```

#### Populating database with data from fixtures
```bash
docker exec -it app_php-fpm_1 symfony console doctrine:fixtures:load -n
```

#### Populating product database with products from https://fakestoreapi.com/
```bash
docker exec -it docker_php-fpm_1 bin/console app:update-product-database
```

### Testing
```bash
docker exec -it docker_php-fpm_1 bin/phpunit
```


# lightsail-docker-compose-test

```
(cp -R ./public ./docker/nginx/public && rm -rf ./docker/php-fpm/app && cd docker/ && docker-compose -f docker-compose.yml up --build)
```

## todo:
- db
- https
- different config - dev/prod
- timezones
- prefixes, ${domain}
- przejrzyj configi
- daj cv na jdarda.pl
  - a ten projekt na jdarda.pl/shiplink-recruitment
- optimise php/nginx/symfony for prod?
- entrypoint stuff?

https://github.com/dunglas/symfony-docker
https://blog.devsense.com/2019/php-nginx-docker
https://docs.docker.com/compose/extends/


nauczylem sie
- lightsail
- github actions
- github containers
