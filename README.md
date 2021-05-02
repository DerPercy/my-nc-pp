#

## (Re-)build the docker-compose.yml
```
sudo docker-compose build
```

## Starting development server
```
sudo docker-compose up -d
```
## Build Vue app
```
make build-js
```

## Test Vue app
```
make test-js
```

## Run PHPUnit
```
sudo docker-compose run phpunit ./phpunit /app/custom_apps/myppapp/tests --color=auto
```

## Stopping development server
```
sudo docker stop my_nextcloud
```

## Database Migrations
```
make nc-bash

php ./occ migrations:generate myppapp 1000
php ./occ migrations:status myppapp
```
See:
Migrations: https://docs.nextcloud.com/server/13.0.0/developer_manual/app/migrations.html
Schema: https://docs.nextcloud.com/server/latest/developer_manual/app_development/tutorial.html


# Attic
## Testing
### Install PHPunit
https://phpunit.de/manual/5.7/en/installation.html
(later PHPUnits caused an error)

sudo docker-compose up -d
docker-compose exec my_phpunit php /usr/local/bin/phpunit



### Find PHPUnit on Container
sudo docker-compose run composer find / -name "phpunit"
sudo docker-compose run composer find / -name "phpunit"

# Thanks to
https://github.com/skjnldsv/vueexample
