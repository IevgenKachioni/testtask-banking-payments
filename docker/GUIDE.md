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

