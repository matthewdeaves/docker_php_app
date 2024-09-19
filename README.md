# Example PHP/Nginx/MySQL/RabbitMQ App with Docker
This is a template project to get a PHP/Nginx/MySQL/PHPMyAdmin/RabbitMQ development environment up and running via Docker on your local machine.

## Using Docker

### Getting the environment up and running with Docker
Make sure you have Docker installed on your machine.

Get the code from this repository and fire up the containers with Docker:

```
git clone git@github.com:matthewdeaves/docker_php_app.git

cd docker_php_app

docker compose up
```

Some more Docker commands and what they do:

#### Start containers 
`docker compose up`

#### Stop running containers
`docker compose stop`

#### Stop and remove containers, networks, volumes and images
`docker compose down`

#### Down and remove all volumes in dockerfile and those not
`docker compose down -v`

#### Down and remove images that don't have a custom tag
`docker compose down --rmi <all|local>`

### Making changes to Docker containers and related files
If you make changes to `docker-compose.yml` and/or related docker files (such as editing `docker/php/Dockerfile` to install more packages on to the virtual machine image) then you will need to recreate the images and rebuild them from scratch. Do `docker compose down -v` to remove all running containers and then `docker compose up --build --force-recreate --remove-orphans` to make sure everything (including the container base images via --build flag) get created from scratch.

You might need to use `sudo` as do on Ubuntu 24.

### Useful Docker commands
1. `sudo docker container ls --all` list all Docker containers on your machine
2. `sudo docker exec -it {CONTAINER_NAME} /bin/sh` open an interactive shell on a given container. For example with the default `docker-composer.yml` you can use `docker exec -it docker_php_app-nginx-1 /bin/sh` to open an interactive shell on to the Nginx container.
3. `sudo docker image prune -a` if you get any error about max depth exceeded when doing a docker compsoe up.

## MySQL Specific Setup

### Listens on Port 3310
So as not to clash with any existing MySQL Server installation on your host machine, the MySQL container is setup to use port `3333` on the host machine and forward to `3306` on the container. Access MySQL via your preferred tool on `127.0.0.1` or `localhost` using port `3333` with the `root` user with password `password`.

### MySQL Initialisation Script
`docker/mysql/init.sql` has a default setup of SQL to be run once MySQL is up and runnining. It recreates `example` database and an `app` user allowing connections from localhost and anywhere (`%`) and with all permissions granted.

### phpMyAdmin

You can visit `http://localhost:8001` to get to phpMyAdmin.

## Nginx Specific Setup

### Listens on Port 8000
So as not to clash with any existing Nginx  installation on your host machine, the Nginx container is setup to use port `8000` on the host machien and forward to `80` on the container. Access the hosted site via `http://localhost:8080/`

Your code goes in the `src` folder and is shared via a docker volume into `/var/www/html/`. This allow you to make changes to your code in `src` and refresh the site in your browser to see live changes.

### Nginx default.conf
`docker/nginx/default.conf` is copied to the container to setup the basic Nginx server configuration. If you need to modify this and recreate the image and container, modify that file and then run `sudo docker-compose down -v` followed by `sudo docker compose up --build --force-recreate --remove-orphans`. Warning data will be lost! `docker/nginx/Dockerfile` copies `default.conf` into place. The default configuration is to use port `9000` for the php-fpm process.

## PHP Specific Setup

### PHP/HTML Code
Put your code in the `src` folder. By default there is an index.php file in there with a `phpinfo();` call. Make changes to you code in this folder, refresh the site in your browser to see changes.

### Test MySQL Connectivity

Visit `http://localhost:8000/mysql_test.php` to test connectivity to the MySQL Server container. You should see user1 and user2 lsited on the page. Check the source of that file to see that the records are pulled from MySQL.

### PHP 8.2.x with some extra modules
Extra PHP Modules installed are:
1. imagick
2. mysqli 
3. pdo
4. pdo_mysql
5. bcmath
6. intl
7. sockets

You can edit `docker/php/Dockerfile` if you don't want all of these but I have them for running CakePHP applications and using Image Magic.

### Executing additional PHP scripts from the container
Sometimes you might want to run PHP scripts from within the container itself, such as a PHP script that is a consumer for a RabbitMQ Queue. Open an interactive shell to the PHP container `sudo docker exec -it docker_php_app-php-1 /bin/sh` which will by default cd you into `/var/www/html` and from there you can do `php somefile.php` to execute a script. You can also run any other command you like as it is a terminal. Type `exit` to leave the container and return to your own shell.

## RabbitMQ Specific Setup

### Admin interface on Port 15674
So as not to clash with any existing RabbitMQ installation on your host machine, the RabbitMQ admin interface is setup to run on port `15674` and you can use `http://localhost:15674` in your browser to access it. The default login is `admin` with `password`. 

### Listens on Port 5674
So as not to clash with any existing RabbitMQ installation on your host machine, the RabbitMQ service is setup to listen on port `5674`.

### Test RabbitMQ Connectivity
Visit `http://localhost:8000/rabbitmq_test.php` and if all is well the page will be blank and you'll see in the RabbitMQ interface that the test_queue was created and a mesasge was put onto the queue for each page reload.
