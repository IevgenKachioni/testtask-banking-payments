DB dump:
The database dump is in /docker/mysql/dump.sql (will be used by Docker automatically).


Running CLI commands:

- Transfer CLI Command example: php ./index.php transfer 1 2 20000
(The parameters are: the sender user id, the receiver id, the amount.)

- Operations overview CLI command example: php ./index.php operations 1
(The parameter is the user id.)

- Funds withdraw CLI command example: php ./index.php withdraw 1 20000
(The parameters are: the user id, the amount.)

- Funds add to the account CLI command example: php ./index.php add_funds 1 20000
(The parameters are: the user id, the amount.)


Running PHPUnit tests (go into Docker container, first, as shown below):
php  vendor/bin/phpunit --testsuite payments


DOCKER:

1) Start:
docker-compose up

2) Go to container:
docker exec -it payment_php /bin/bash
su application
cd /var/www

3) DB creds (always use root user and “root” DB password):
MYSQL_ROOT_USER: root
MYSQL_ROOT_PASSWORD: root123
MYSQL_DATABASE: payment_db

DB connect (using MySQL Workbench):
- Connection method: Standard (TCP/IP)
- Hostname: 127.0.0.1
- Port: 3315
- Username: root
- Password: root123
- Default schema: payment_db