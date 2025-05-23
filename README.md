# Curotec 2 - Project Setup & Usage

This project is a Laravel + Vue.js (Inertia) application for managing posts.

---

## 🚀 Getting Started (with Docker)

### 1. **Requirements**

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)

---

### 2. **Clone the Repository**

```bash
git clone https://github.com/william-ben-yuan/curotec
cd curotec
```

---

### 3. **Start Docker Containers**

```bash
docker-compose up -d
```

-   The app will be available at [http://localhost:8000](http://localhost:8000)
-   Vite dev server (for hot reload) will be at [http://localhost:5173](http://localhost:5173)

---

### 4. **Install Dependencies**

#### Backend (Laravel)

```bash
docker-compose exec app composer install
```

#### Frontend (Vue.js)

```bash
docker-compose exec app npm install
```

---

### 5. **Environment Setup**

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Generate the application key:

```bash
docker-compose exec app php artisan key:generate
```

Edit `.env` if needed (database, etc).  
For Docker, use:

```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=postgres
DB_PASSWORD=secret
```

---

### 6. **Database Migration & Seeding**

```bash
docker-compose exec app php artisan migrate --seed
```

---

### 7. **Build Frontend Assets**

**Development (hot reload):**

```bash
docker-compose exec app npm run dev
```

**Production:**

```bash
docker-compose exec app npm run build
```

---

### 8. **Running Tests**

**Feature & Unit Tests (using Pest):**

```bash
docker-compose exec app php artisan test
```

or

```bash
docker-compose exec app ./vendor/bin/pest
```

---

## 📝 Useful Docker Commands

-   **Access Laravel container bash:**
    ```bash
    docker-compose exec app bash
    ```
-   **Run artisan commands:**
    ```bash
    docker-compose exec app php artisan <command>
    ```
-   **Run npm scripts:**
    ```bash
    docker-compose exec app npm run <script>
    ```
-   **Check logs:**
    ```bash
    docker-compose logs
    ```

---

## 📚 Additional Notes

-   Default credentials for seeded users can be found in the database seeders/factories.
-   If you change dependencies, rebuild the container with:
    ```bash
    docker-compose build --no-cache
    ```

---

Happy coding! 🚀
