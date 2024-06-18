install: build-images set-up ## Install project
fill: fill-table ## Fill out table A
send: add-user set-administrator set-permissions send-data start-worker ## Send table A to table B

build-images:
	docker compose build --no-cache

set-up:
	docker compose up --pull always -d --wait

update-db:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec php bash -c "php bin/console doctrine:schema:update --force"

fill-table:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec php bash -c "php bin/console app:fill-table"

send-data:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec php bash -c "php bin/console app:send-data"

start-worker:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec php bash -c "php bin/console messenger:consume --all"

add-user:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec rabbitmq bash -c "rabbitmqctl add_user myuser mypassword"

set-administrator:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec rabbitmq bash -c "rabbitmqctl set_user_tags myuser administrator"

set-permissions:
	docker-compose --env-file .env -f compose.yaml -f compose.override.yaml exec rabbitmq bash -c 'rabbitmqctl set_permissions -p / myuser ".*" ".*" ".*"'
