up:
	docker-compose up -d
build:
	docker-compose build --no-cache --force-rm --parallel
build-app:
	docker-compose build --no-cache --force-rm --parallel app
build-app-queue-worker:
	docker-compose build --no-cache --force-rm --parallel app-queue-worker
build-web:
	docker-compose build --no-cache --force-rm --parallel web
build-db:
	docker-compose build --no-cache --force-rm --parallel db
build-kvs:
	docker-compose build --no-cache --force-rm --parallel kvs
laravel-install:
	docker-compose exec app composer create-project --prefer-dist laravel/laravel .
create-project:
	@make build
	@make up
	@make laravel-install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan storage:link
	@make fresh
install-recommend-packages:
	docker-compose exec app composer require doctrine/dbal "^2"
	docker-compose exec app composer require --dev ucan-lab/laravel-dacapo
	docker-compose exec app composer require --dev barryvdh/laravel-ide-helper
	docker-compose exec app composer require --dev beyondcode/laravel-dump-server
	docker-compose exec app composer require --dev barryvdh/laravel-debugbar
	docker-compose exec app composer require --dev roave/security-advisories:dev-master
	docker-compose exec app php artisan vendor:publish --provider="BeyondCode\DumpServer\DumpServerServiceProvider"
	docker-compose exec app php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
init:
	docker-compose up -d --build
	docker-compose exec app composer install
	docker-compose exec app cp .env.example .env
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan storage:link
	@make fresh
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
destroy:
	docker-compose down --rmi all --volumes --remove-orphans
destroy-volumes:
	docker-compose down --volumes --remove-orphans
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
log-app:
	docker-compose logs app
log-app-watch:
	docker-compose logs --follow app
log-db:
	docker-compose logs db
log-db-watch:
	docker-compose logs --follow db
web:
	docker-compose exec web ash
app:
	docker-compose exec app bash
app-queue-worker:
	docker-compose exec app-queue-worker bash
kvs:
	docker-compose exec kvs ash
redis-cli:
	docker-compose exec kvs ash -c 'redis-cli'
migrate:
	docker-compose exec app php artisan migrate
fresh:
	docker-compose exec app php artisan migrate:fresh --seed
seed:
	docker-compose exec app php artisan db:seed
dacapo:
	docker-compose exec app php artisan dacapo
rollback-test:
	docker-compose exec app php artisan migrate:fresh
	docker-compose exec app php artisan migrate:refresh
tinker:
	docker-compose exec app php artisan tinker
test:
	docker-compose exec app php artisan test
optimize:
	docker-compose exec app php artisan optimize
optimize-clear:
	docker-compose exec app php artisan optimize:clear
cache:
	docker-compose exec app composer dump-autoload -o
	@make optimize
	docker-compose exec app php artisan event:cache
	docker-compose exec app php artisan view:cache
cache-clear:
	docker-compose exec app composer clear-cache
	@make optimize-clear
	docker-compose exec app php artisan event:clear
npm:
	@make npm-install
npm-install:
	docker-compose exec web npm install
npm-dev:
	docker-compose exec web npm run dev
npm-watch:
	docker-compose exec web npm run watch
npm-watch-poll:
	docker-compose exec web npm run watch-poll
npm-hot:
	docker-compose exec web npm run hot
yarn:
	docker-compose exec web yarn
yarn-install:
	@make yarn
yarn-dev:
	docker-compose exec web yarn dev
yarn-watch:
	docker-compose exec web yarn watch
yarn-watch-poll:
	docker-compose exec web yarn watch-poll
yarn-hot:
	docker-compose exec web yarn hot
db:
	docker-compose exec db bash
sql:
	docker-compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
sql-root:
	docker-compose exec db bash -c 'mysql -u root -p$$MYSQL_ROOT_PASSWORD $$MYSQL_DATABASE'
redis:
	docker-compose exec redis redis-cli
ide-helper:
	docker-compose exec app php artisan clear-compiled
	docker-compose exec app php artisan ide-helper:generate
	docker-compose exec app php artisan ide-helper:meta
	docker-compose exec app php artisan ide-helper:models --nowrite
kubeapply:
#	kubectl apply -f k8s-local-secret.yaml && export "LOCAL_DTUJ_PROJECT_PATH=`pwd`" && envsubst < k8s-local.yaml | kubectl apply -f -
	kubectl apply -f k8s-local-secret.yaml && kubectl apply -f k8s-local.yaml
kubedelete:
#	export "LOCAL_DTUJ_PROJECT_PATH=`pwd`" && envsubst < k8s-local.yaml | kubectl delete -f - && kubectl delete -f k8s-local-secret.yaml
	kubectl delete -f k8s-local.yaml && kubectl delete -f k8s-local-secret.yaml
docker-inside-minikube:
	eval $$(minikube docker-env)
minikube-up:
	if [ "`docker container ls -a --format '{{.Names}}' | grep -x 'minikube'`" = "minikube" ]; then \
		minikube start; \
	else \
		minikube start --mount --mount-string `pwd`:/host/dtuj --nodes 2; \
		minikube ssh -- sudo mkdir /data/mysql; \
		minikube ssh -- sudo chown 27:27 /data/mysql; \
		minikube ssh --node minikube-m02 -- sudo mkdir /data/mysql; \
		minikube ssh --node minikube-m02 -- sudo chown 27:27 /data/mysql; \
		minikube ssh -- sudo mkdir /data/redis; \
		minikube ssh -- sudo chown 999:1000 /data/redis; \
		minikube ssh --node minikube-m02 -- sudo mkdir /data/redis; \
		minikube ssh --node minikube-m02 -- sudo chown 999:1000 /data/redis; \
	fi
minikube-down:
	minikube stop
minikube-delete:
	minikube delete --all
minikube-build:
	minikube ssh -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/php --output type=image,name=docker.io/hiroaki2020/dtuj_app:1.0,push=false
	minikube ssh -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/nginx --output type=image,name=docker.io/hiroaki2020/dtuj_web:1.0,push=false
	minikube ssh -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/mysql --output type=image,name=docker.io/hiroaki2020/dtuj_db:1.0,push=false
	minikube ssh -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/xtrabackup --output type=image,name=docker.io/hiroaki2020/dtuj_xtrabackup:1.0,push=false
	minikube ssh -n minikube-m02 -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/php --output type=image,name=docker.io/hiroaki2020/dtuj_app:1.0,push=false
	minikube ssh -n minikube-m02 -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/nginx --output type=image,name=docker.io/hiroaki2020/dtuj_web:1.0,push=false
	minikube ssh -n minikube-m02 -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/mysql --output type=image,name=docker.io/hiroaki2020/dtuj_db:1.0,push=false
	minikube ssh -n minikube-m02 -- buildctl build --frontend=dockerfile.v0 --local context=/host/dtuj --local dockerfile=/host/dtuj/infra/docker/xtrabackup --output type=image,name=docker.io/hiroaki2020/dtuj_xtrabackup:1.0,push=false
circleci-local-build:
	circleci config process .circleci/config.yml > process.yml
	circleci local execute -c process.yml local_build
