# How to setup local 'skeleton-shop-api'

### Build up and launch docker container
```
docker-compose up -d
```

### Create database if not exists
```
docker-compose exec app php bin/console doctrine:database:create
```

### Migrate database data 
```
docker-compose exec app php bin/console doctrine:migrations:migrate
```
