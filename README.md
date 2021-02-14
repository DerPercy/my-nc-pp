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

## Run PHPUnit
```
sudo docker-compose run phpunit ./phpunit /app/custom_apps/myppapp/tests --color=auto
```

## Stopping development server
```
sudo docker stop my_nextcloud
```

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
