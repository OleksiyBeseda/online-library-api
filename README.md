# Online Library API

This project is a Symfony-based REST API for managing an online library. It uses Docker for environment isolation and PostgreSQL as the database.

---

## 🚀 Getting Started

### 1️⃣ Prerequisites

Ensure you have the following installed on your Mac:
- **Docker & Docker Compose**
- **Git**

### 2️⃣ Project Setup

Follow these steps to get the project running from scratch:

#### 1. Clone the repository
```bash
git clone <your-repo-url>
cd online-library-api
```

#### 2. Start Docker containers
This command builds the application image and starts the `app` and `db` services.
```bash
docker compose up -d
```
> **Host Command:** Run this on your Mac.

#### 3. Install dependencies
Enter the `app` container and run composer install.
```bash
docker exec -it app composer install
```
> **Host Command:** Run this on your Mac to execute inside the container.

#### 4. Configure Environment
Ensure your `.env` file (or `.env.local`) has the correct database connection:
```env
DATABASE_URL="postgresql://library_user:library_pass@db:5432/library?serverVersion=15&charset=utf8"
```
*(The default `DATABASE_URL` in `.env` is already configured for Docker).*

#### 5. Initialize Database and Migrations
Run these commands to create the database and apply migrations:
```bash
# Create the database (if it doesn't exist)
docker exec -it app php bin/console doctrine:database:create --if-not-exists

# Run migrations
docker exec -it app php bin/console doctrine:migrations:migrate --no-interaction
```

### 3️⃣ Verify the Installation

- **API Base URL:** [http://localhost:8000/api](http://localhost:8000/api)
- **Database Connection:** 
  You can connect to PostgreSQL using any client (like DBeaver or `psql`):
  - **Host:** `localhost`
  - **Port:** `5433`
  - **User:** `library_user`
  - **Password:** `library_pass`
  - **Database:** `library`

---

## 📖 API Documentation (Swagger/OpenAPI)

The API is documented using the OpenAPI 3.0 specification.

1. Locate the `api.yaml` file in the project root.
2. To view it in a friendly UI, you can:
   - Use the [Swagger Editor](https://editor.swagger.io/) and paste the content of `api.yaml`.
   - Install a browser extension like "Swagger UI Viewer".
   - Run a local Swagger UI container:
     ```bash
     docker run -p 8080:8080 -e SWAGGER_JSON=/app/api.yaml -v $(pwd):/app swaggerapi/swagger-ui
     ```
     Then open [http://localhost:8080](http://localhost:8080).

---

## 🛠 Useful Docker Commands

| Action | Command |
| :--- | :--- |
| **Start containers** | `docker compose up -d` |
| **Stop containers** | `docker compose down` |
| **Rebuild images** | `docker compose build` |
| **View status** | `docker compose ps` |
| **View logs** | `docker compose logs -f` |
| **Shell inside app** | `docker exec -it app bash` |
| **Check DB tables** | `docker exec -it postgres psql -U library_user -d library -c "\dt"` |

---

## 💡 Notes for Mac Users

- All `docker exec` commands should be run from your terminal on the host machine.
- If you encounter `Could not open input file: bin/console`, ensure you are in the project root and that the Docker volume mapping is correct in `docker-compose.yml`:
  ```yaml
  volumes:
    - .:/var/www/html
  ```
