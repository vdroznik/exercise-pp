init:
	docker compose up -d
	docker compose exec app composer install

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

migrate-db:
	docker compose exec app vendor/bin/doctrine-migrations migrate -n
	docker compose exec app vendor/bin/doctrine-migrations --db-configuration=migrations-db-test.php migrate -n

generate-import-promos:
	docker compose exec app bin/promo generate-promos | true
	docker compose exec db mysql -u root -D promo -e "LOAD DATA INFILE '/var/lib/mysql-files/exchange/promocodes.csv' INTO TABLE promocodes (@promo) SET code=@promo"

test:
	docker compose exec app vendor/bin/phpunit --testdox

fix:
	docker compose exec app vendor/bin/php-cs-fixer fix

ssh:
	docker compose exec app /bin/sh

remove:
	docker compose down
	docker volume remove exercise-promo_db-data || true
	docker image remove exercise-promo-app || true
