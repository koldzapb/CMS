# CMS Test Project

This is a simplified CMS project built with Laravel 8, PHP 8, Composer 2, and MySQL 5.7.  
The project involves two main entities: **Post** and **Comment**, along with API endpoints for retrieving, creating, and deleting records.  
It is structured with dedicated layers for Services, Repositories, and DTOs, and includes integrated tests.

## Technologies

- PHP 8
- Laravel 8
- Composer 2
- MySQL 5.7

## Installation

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

## Running the Application

Start the local development server:
```bash
php artisan serve
```

By default, it will run at:  
[http://127.0.0.1:8000](http://127.0.0.1:8000)

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

## Testing

Tests are divided into `Unit` and `Feature` tests:
- **Unit tests** focus on business logic in services and repositories, often using mocks.
- **Feature tests** focus on the API endpoints and may require a test database.

Run the tests:
```bash
php artisan test
```
or
```bash
vendor/bin/phpunit
```


## Code Structure

- `app/Models` - Eloquent models (Post, Comment)
- `app/Http/Controllers` - Controllers for Post and Comment
- `app/Services` - Business logic and application services
- `app/Repositories` - Data access layer (repositories)
- `app/Dto` - Data Transfer Objects for passing parameters
- `tests/Unit` - Unit tests (service logic, repository tests)
- `tests/Feature` - Feature tests (API endpoint tests)

## Additional Notes

- DTO classes (`CommentSearchByCriteriaDto`, `PostSearchByCriteriaDto`) simplify passing parameters to services.
- The service and repository layers improve code maintainability and testability.
- Mock-based tests (using Mockery) demonstrate how to test logic in isolation.