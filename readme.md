## Instructions
The simplest way to put this application to run locally for development uses [**docker-compose**](https://docs.docker.com/compose/).
For that, it is necessary to have both [**docker**](https://docs.docker.com/get-docker/) and [**docker-compose**](https://docs.docker.com/compose/) installed locally.
Administrator privilege may be required to execute commands related to **docker** and **docker-compose**.

### Create **.env** file
```
cp .env.example .env
```

It may be necessary to make some changes to the values of some variables, according to your local environment.

### Execute docker-compose
```
docker-compose up -d
```

After executing this command, the application and database containers will have already been initialized.

### List running containers
```
docker ps
```

### Create database
Connect to the database container to create a database for the application. You can do this using phpMyAdmin or MySQL Workbench, for example. The database container will be running on port 3306 (MySQL standard). The password for the standard user **root** can be found in the **docker-compose.yml** file.  The default name of the database is **my_products**, but you can use any other name.

After creating the database, you must update the environment variables related to the database connection present in the **.env** file. At that time, the database will be empty. It will be populated later after the migrations are performed.

## Configure application
The value of the **CONTAINER ID** property of the application container should be used in the next commands in place of **<container_id>**.

### Install composer in the application container
```
docker exec <container_id> bash -c "curl --silent --show-error https://getcomposer.org/installer | php"
```

### Execute composer in the application container
```
docker exec <container_id> bash -c "php composer.phar install"
```

### Give permissions to the storage directory
```
docker exec <container_id> bash -c "chown -R www-data:www-data /var/www/html/storage"
```

### Execute migrations
```
docker exec <container_id> bash -c "php artisan migrate:fresh --seed"
```
