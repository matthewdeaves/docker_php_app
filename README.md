# Example PHP/Nginx/MySQL/RabbitMQ App with Docker
This is a template project to get a PHP/Nginx/MySQL/RabbitMQ environment up and running via Docker on your local machine.

## Docker

### Getting the environment up and running with Docker
Make sure you have Docker installed on your machine.

Get the code from this repository, `cd` into the folder and run `sudo docker compose up`. This will have Docker use the docker-compose.yml and related dockerfile to create images for the containers and fire them up.

Some more Docker commands and what they do:

#### Start services 
`docker-compose up`

#### Stop services only
`docker-compose stop`

#### Stop and remove containera, networks etc
`docker-compose down`

#### Down and remove volumes
`docker-compose down --volumes`

#### Down and remove images that don't have a custom tag
`docker-compose down --rmi <all|local>`

### Making changes to Docker containers and related files
If you make changes to `docker-compose.yml` and/or related docker files (such as editing `docker/php/Dockerfile` to install more packages on to the virtual machine image) then you will need to recreate the images and rebuild them from scratch. Do `sudo docker-compose down -v` to remove all running containers and then `sudo docker compose up --build --force-recreate --remove-orphans` to make sure everything (including the container base images via --build flag) get created from scratch.

You might not need to use `sudo` but on my Ubuntu 24 machine I did.

### Useful Docker commands
1. `sudo docker ps -a` list all Docker containers on your machine
2. `sudo docker exec -it {CONTAINER_NAME} /bin/sh` open an interactive shell on a given container for example with the default `docker-composer.yml` you can use `sudo docker exec -it php_container /bin/sh` to open an interactive shell on to the PHP container.
3. `sudo docker image prune -a` if you get any error about max depth exceeded when doing a docker compsoe up.

## MySQL Specific Setup

### Listens on Port 3310
So as not to clash with any existing MySQL Server installation on your host machine, the MySQL container is setup to use port `3310` on the host machine and forward to `3306` on the container. Access MySQL via your preferred tool on `127.0.0.1` or `localhost` using port `3310` with the `root` user. The `root` user has the password `password` by default.

### MySQL Initialisation Script
`docker/mysql/init.sql` has a default setup of SQL to be run once MySQL is up and runnining. It recreates `example` database and an `app` user allowing connections from localhost and anywhere (`%`) and with all the permissions possible.

## Nginx Specific Setup

### Listens on Port 8080
So as not to clash with any existing Nginx  installation on your host machine, the Nginx container is setup to use port `8080` on the host machien and forward to `80` on the container. Access the hosted site via `http://localhost:8080/`

Everything in the `src` folder linked into `/var/www/html/` and you can make changes to your local copy of `src`, refresh the site in your browser and see live changes.

### Nginx default.conf
`docker/nginx/default.conf` is copied to the container to setup the basic Nginx server configuration. You can modify this and then run `sudo docker-compose down -v` followed by `sudo docker compose up --build --force-recreate --remove-orphans`. Warning data will be lost! `docker/nginx/Dockerfile` copies `default.conf` into place. The default configuration is to use port `9000` for the php-fpm process.

## PHP Specific Setup

### PHP/HTML Code
Put your code in the `src` folder. By default there is an index.php file in there with a `phpinfo();` call. Make changes to you code in this folder, refresh the site in your browser to see changes.

### Test MySQL Connectivity

Visit `http://localhost:8080/mysql_test.php` to test connectivity to the MySQL Server container.

### PHP 8.3.x with some extra modules
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
Sometimes you might want to run PHP scripts from within the container itself, such as a PHP script that is a consumer for a RabbitMQ Queue. Open an interactive shell to the PHP container `sudo docker exec -it php_container /bin/sh` which will by default cd you into `/var/www/html` and from there you can do `php somefile.php` to execute a script. You can also run any other command you like as it is a terminal. Type `exit` to leave the container and return to your own shell.

## RabbitMQ Specific Setup

### Admin interface on Port 15673
So as not to clash with any existing RabbitMQ installation on your host machine, the RabbitMQ admin interface is setup to run on port `15673` and you can use `http://localhost:15673` in your browser to access it. The default login is `admin` with `password`. 

### Listens on Port 5673
So as not to clash with any existing RabbitMQ installation on your host machine, the RabbitMQ service is setup to listen on port `5673`.

### Test RabbitMQ Connectivity
Visit `http://localhost:8080/rabbitmq_test.php` and if all is well the page will be blank and you'll see in the RabbitMQ interface that the test_queue was created and a mesasge was put onto the queue for each page reload.