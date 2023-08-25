FROM postgres:14-bullseye

COPY init-user-db.sh /docker-entrypoint-initdb.d

RUN chmod +x /docker-entrypoint-initdb.d/*