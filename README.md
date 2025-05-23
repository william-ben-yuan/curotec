# Curotec - Project Setup & Usage

This project is a Laravel + Vue.js (Inertia) application for managing posts.

---

## 🏗️ Architectural Decisions & Patterns

-   **Backend:** Laravel (Repository & Service Pattern)
    -   **Repository Pattern:** All database queries are abstracted in repositories (e.g., `PostRepository`), promoting separation of concerns and easier testing.
    -   **Service Layer:** Business logic is handled in services (e.g., `PostService`), keeping controllers thin and focused on HTTP concerns.
    -   **Request Validation:** Uses Laravel Form Requests for validation (`PostStoreRequest`, `PostUpdateRequest`).
    -   **Inertia.js:** Bridges Laravel and Vue.js, enabling SPA-like navigation with server-side routing.
-   **Frontend:** Vue 3 + Inertia.js
    -   **Component-based:** UI is split into reusable Vue components.
    -   **Pinia:** Used for state management where global state is needed.
    -   **Bootstrap:** For UI styling.
    -   **Vite:** For fast frontend builds and hot reload.

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

## 🧪 Testing Environment

-   By default, tests use a separate database.
-   You should create a `.env.testing` file in your project root with the following content (adjust as needed):

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=laravel_test
    DB_USERNAME=postgres
    DB_PASSWORD=secret
    ```

-   Create the test database in your PostgreSQL container:

    ```bash
    docker-compose exec db psql -U postgres -c "CREATE DATABASE laravel_test;"
    ```

-   Before running tests for the first time, run migrations for the test database:

    ```bash
    docker-compose exec app php artisan migrate --env=testing
    ```

-   When you run tests with `php artisan test` or Pest, Laravel will use this test database and environment automatically.

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

## 📝 Additional Notes

-   All business logic is in the Service layer, not controllers.
-   All database access is via Repositories.
-   For customizations, see the `/app/Services` and `/app/Repositories` folders.
-   For tests, see `/tests/Feature/PostTest.php`.

---

Happy coding! 🚀
