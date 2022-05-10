up:
	docker-compose up -d
pull:
	git pull
build:
	docker-compose build
init:
	docker-compose up -d --build
	docker-compose exec web composer install
	docker-compose exec web cp .env.example .env
	docker-compose exec web php artisan key:generate
	docker-compose exec web php artisan storage:link
	docker-compose exec web chmod -R 777 storage bootstrap/cache
remake:
	@make destroy
	@make init
stop:
	docker-compose stop
down:
	docker-compose down --remove-orphans
restart:
	@make down
	@make up
ps:
	docker-compose ps
logs:
	docker-compose logs
logs-watch:
	docker-compose logs --follow
log-web:
	docker-compose logs web
log-web-watch:
	docker-compose logs --follow web
log-web:
	docker-compose logs web
log-web-watch:
	docker-compose logs --follow web
log-db:
	docker-compose logs db
log-db-watch:
	docker-compose logs --follow db
web:
	docker-compose exec web ash
migrate:
	docker-compose exec web php artisan migrate
fresh:
	docker-compose exec php-fpm php artisan migrate:fresh --seed
seed:
	docker-compose exec php-fpm php artisan db:seed
test:
	docker-compose exec web php artisan test
optimize:
	docker-compose exec web php artisan optimize
optimize-clear:
	docker-compose exec web php artisan optimize:clear
cache:
	docker-compose exec web composer dump-autoload -o
	@make optimize
	docker-compose exec web php artisan event:cache
	docker-compose exec web php artisan view:cache
cache-clear:
	docker-compose exec web composer clear-cache
	@make optimize-clear
	docker-compose exec web php artisan event:clear
ide-helper:
	docker-compose exec web php artisan clear-compiled
	docker-compose exec web php artisan ide-helper:generate
	docker-compose exec web php artisan ide-helper:meta
	docker-compose exec web php artisan ide-helper:models --nowrite
