# CMS Test Project

This is a simplified CMS project built with Laravel 8, PHP 8, Composer 2, and MySQL 5.7.  
The project involves two main entities: **Post** and **Comment**, along with API endpoints for retrieving, creating, and deleting records.  
It is structured with dedicated layers for Services, Repositories, and DTOs, and includes integrated tests.

## Technologies

- PHP 8
- Laravel 8
- Composer 2
- MySQL 5.7
- Docker (via Laravel Sail)

## Features

- RESTful API for Posts and Comments.
- Filtering, Sorting, and Pagination.
- Unit and Feature Tests.
- Support for Docker-based development environment.

---

## Installation

### Option 1: Traditional Installation

1. **Clone the repository**:
   ```bash
   git clone <REPOSITORY_URL>
   cd <PROJECT_NAME>
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Copy the environment file**:
   ```bash
   cp .env.example .env
   ```
   Update the database credentials in `.env` to match your local environment.

4. **Generate the application key**:
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed the database**:
   ```bash
   php artisan migrate --seed
   ```
   This will create the `posts` and `comments` tables and populate them with sample data.

6. **Run the local development server**:
   ```bash
   php artisan serve
   ```

By default, it will run at:  
[http://127.0.0.1:8000](http://127.0.0.1:8000)

---

### Option 2: Using Docker (Laravel Sail)

1. **Clone the repository**:
   ```bash
   git clone <REPOSITORY_URL>
   cd <PROJECT_NAME>
   ```

2. **Install PHP dependencies using Sail**:
   ```bash
   composer install
   ```

3. **Copy the environment file**:
   ```bash
   cp .env.example .env
   ```
   Ensure the following values are correctly set in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=cms_test
   DB_USERNAME=testuser
   DB_PASSWORD=password
   ```

4. **Start the Docker environment**:
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Run migrations and seeders**:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

6. The application will be available at [http://localhost](http://localhost).

---

## API Endpoints

- **GET /api/posts**
  - Retrieves a list of posts with filtering, sorting, pagination, and optional `comment` keyword filtering.
  - Example:
    ```bash
    GET /api/posts?sort=created_at&direction=desc&limit=5&page=1&comment=laughing
    ```

- **DELETE /api/posts/{id}**
  - Deletes a post and its related comments.
  - Example:
    ```bash
    DELETE /api/posts/1
    ```

- **GET /api/comments**
  - Retrieves a list of comments with filtering, sorting, pagination, and optional `post` relation loading.
  - Example:
    ```bash
    GET /api/comments?sort=created_at&direction=asc&with=post
    ```

- **POST /api/comments**
  - Creates a new comment.
  - Request body:
    ```json
    {
      "post_id": 1,
      "content": "This is an awesome comment"
    }
    ```
  - Example:
    ```bash
    POST /api/comments
    ```

- **DELETE /api/comments/{id}**
  - Deletes a specific comment.
  - Example:
    ```bash
    DELETE /api/comments/1
    ```

---

## Testing

Tests are divided into `Unit` and `Feature` tests:
- **Unit tests** focus on business logic in services and repositories, often using mocks.
- **Feature tests** focus on the API endpoints and may require a test database.

Run the tests using:

### Without Docker:
```bash
php artisan test
```
or
```bash
vendor/bin/phpunit
```

### With Docker:
```bash
./vendor/bin/sail artisan test
```

---

## Code Structure

- `app/Models` - Eloquent models (Post, Comment).
- `app/Http/Controllers` - Controllers for Post and Comment.
- `app/Services` - Business logic and application services.
- `app/Repositories` - Data access layer (repositories).
- `app/Dto` - Data Transfer Objects for passing parameters.
- `tests/Unit` - Unit tests (service logic, repository tests).
- `tests/Feature` - Feature tests (API endpoint tests).

---

## Additional Notes

- DTO classes (`CommentSearchByCriteriaDto`, `PostSearchByCriteriaDto`) simplify passing parameters to services.
- The service and repository layers improve code maintainability and testability.
- Mock-based tests (using Mockery) demonstrate how to test logic in isolation.
