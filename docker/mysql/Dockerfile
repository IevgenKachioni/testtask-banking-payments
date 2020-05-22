FROM mysql:5.6.38
COPY ./my.cnf /etc/mysql/my.cnf
ADD ./dump.sql /docker-entrypoint-initdb.d
