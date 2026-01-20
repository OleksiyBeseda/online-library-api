Doctrine:

# Online Library API

This project is a Symfony-based API for managing an online library, using Docker for environment isolation and PostgreSQL as the database.

---

## Prerequisites

- Docker & Docker Compose installed
- Git

---

## Project Setup

### 1️⃣ Clone the repository

```bash
git clone <your-repo-url>
cd online-library-api

2️⃣ Start Docker containers
docker compose up -d


This will start two services:

app – Symfony application

db – PostgreSQL database

Check the containers:

docker compose ps


Both should have status Up.

3️⃣ Enter the application container
docker exec -it app bash


Now you are inside the container, which already has PHP and Composer installed.

4️⃣ Install PHP dependencies
composer install

5️⃣ Configure the database

Ensure your .env or .env.local contains the correct DATABASE_URL:

DATABASE_URL=pgsql://library_user:library_pass@db:5432/library

6️⃣ Create the database

If the database already exists, skip this step.
To recreate the database:

export PGPASSWORD=library_pass
psql -h db -U library_user -d postgres -c "DROP DATABASE IF EXISTS library;"
psql -h db -U library_user -d postgres -c "CREATE DATABASE library;"

7️⃣ Apply Doctrine migrations
php bin/console doctrine:migrations:migrate


When prompted:

WARNING! You are about to execute a migration ...
Are you sure you wish to continue? (yes/no) [yes]:


Type:

yes


This will create the necessary tables and schema in the database.

8️⃣ Verify setup

Access the app at: http://localhost:8000

Check the database tables:

psql -h db -U library_user -d library
\dt

9️⃣ Useful Docker commands
# Stop and remove containers
docker compose down

# Rebuild containers
docker compose build

# View running containers
docker compose ps

# Access the app container
docker exec -it app bash

Notes

The app container is configured to have PHP and Composer installed.

Use .env.local to override sensitive variables without committing them.

Always ensure DATABASE_URL points to the db service inside Docker, not 127.0.0.1.
