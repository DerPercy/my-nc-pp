#

## Starting development server
sudo docker-compose up -d

## Stopping development server
sudo docker stop my_nextcloud


## Testing
### Install PHPunit
https://phpunit.de/manual/5.7/en/installation.html
(later PHPUnits caused an error)

sudo docker-compose up -d
docker-compose exec my_phpunit php /usr/local/bin/phpunit


## Docker PHPUnit
sudo docker-compose run composer composer require --dev phpunit/phpunit
sudo docker-compose run composer ./vendor/bin/phpunit ./custom_apps/myppapp/tests --color=auto


### Find PHPUnit on Container
sudo docker-compose run composer find / -name "phpunit"
sudo docker-compose run composer find / -name "phpunit"

# Thanks to
https://github.com/skjnldsv/vueexample
