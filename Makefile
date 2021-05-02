# weiteres Makefile: ./custom_apps/myppapp

nc-bash:
#	sudo docker-compose up -d
#	sudo docker-compose run my_nextcloud bash
	sudo docker exec -ti --user www-data my_nextcloud /bin/bash
