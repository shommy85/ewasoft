#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
	CREATE USER user_service_user WITH PASSWORD 'user_pwd';
	CREATE DATABASE user_service_db;
	GRANT ALL PRIVILEGES ON DATABASE user_service_db TO user_service_user;

	CREATE USER posts_service_user WITH PASSWORD 'posts_pwd';
	CREATE DATABASE posts_service_db;
  GRANT ALL PRIVILEGES ON DATABASE posts_service_db TO posts_service_user;

  CREATE USER likes_service_user WITH PASSWORD 'likes_pwd';
  CREATE DATABASE likes_service_db;
  GRANT ALL PRIVILEGES ON DATABASE likes_service_db TO likes_service_user;

  CREATE USER direct_coms_service_user WITH PASSWORD 'direct_coms_pwd';
  CREATE DATABASE direct_coms_service_db;
  GRANT ALL PRIVILEGES ON DATABASE direct_coms_service_db TO direct_coms_service_user;
EOSQL