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

### Load default data into database
```
docker-compose exec app php bin/console app:load-example-data
```

### Handle privileges for user marcel
```
docker-compose exec database mysql -u root -p

CREATE DATABASE marcel_test;

GRANT ALL PRIVILEGES ON marcel_test.* TO 'marcel'@'%';

FLUSH PRIVILEGES;

EXIT;
```

### Init database for tests
```
docker-compose exec app php bin/console doctrine:migrations:migrate --env=test
```

### Start unit tests
```
docker-compose exec app php bin/phpunit
```
