#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
	CREATE USER user_service_user WITH PASSWORD 'user_pwd';
	CREATE DATABASE user_service_db;
	GRANT ALL PRIVILEGES ON DATABASE user_service_db TO user_service_user;
EOSQL