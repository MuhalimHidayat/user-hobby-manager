# User Hobby Manager

A simple Laravel application to manage **users** and their **hobbies** (one-to-many relation), with:

- a **Blade** web interface (list, create, edit, delete users together with their hobbies), and
- a **REST API** for the same CRUD operations, secured with **JWT authentication**.

## Features

- CRUD for users, where each user can have many hobbies.
- Hobbies are created, updated (add/remove) and deleted together with the user in a single database transaction.
- Deleting a user also deletes their hobbies (`ON DELETE CASCADE`).
- Blade UI: one page containing the user table (with hobbies, Edit and Delete actions) and the user form.
- JWT-protected API: register, login, logout, refresh token, current user, and user CRUD.
- Business logic lives in service classes (`UserService`, `AuthService`); validation lives in Form Requests; API responses use an API Resource.

## Tech Stack

- PHP / Laravel (see `composer.json` for exact versions)
- MySQL
- [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- Blade, Tailwind CSS, Vite

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── UserController.php          # Blade controller
│   │   └── Api/
│   │       ├── AuthController.php      # JWT auth endpoints
│   │       └── UserController.php      # User CRUD API
│   ├── Requests/                       # StoreUserRequest, UpdateUserRequest, RegisterRequest
│   └── Resources/UserResource.php
├── Models/                             # User, Hobby
└── Services/                           # UserService, AuthService
resources/views/                        # layouts/app.blade.php, users/index.blade.php, users/form.blade.php
routes/                                 # web.php, api.php
```

## Requirements

- PHP 8.3 or newer (check `composer.json`)
- Composer
- MySQL
- Node.js and npm (for the Tailwind/Vite assets)

## Installation

```bash
# 1. Clone and enter the project
git clone <repository-url> user-hobby-manager
cd user-hobby-manager

# 2. Install dependencies
composer install
npm install

# 3. Environment file
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Set your database connection in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_hobby_manager
DB_USERNAME=root
DB_PASSWORD=
```

Create the database, then migrate and seed:

```bash
php artisan migrate --seed
```

Build the frontend assets and start the server:

```bash
npm run build        # or: npm run dev (during development)
php artisan serve
```

The app is now available at <http://localhost:8000>.

## Web Interface (Blade)

Open <http://localhost:8000/users>.

| Action                       | How                                                                 |
| ---------------------------- | ------------------------------------------------------------------- |
| List users and their hobbies | Table on the main page                                              |
| Add a user                   | Fill in the form (name, email, hobbies) and submit                  |
| Edit a user                  | Click **Edit** next to the user; add or remove hobbies, then submit |
| Delete a user                | Click **Delete** (the user's hobbies are deleted as well)           |

Hobbies are entered as a comma-separated list, for example `Reading, Hiking`.

The web interface does not require login. JWT authentication protects the API only.

## API

Base URL: `http://localhost:8000/api`

Send `Accept: application/json` with every request. Protected endpoints also need
`Authorization: Bearer <token>`.

| Method    | Endpoint      | Auth | Description                         |
| --------- | ------------- | ---- | ----------------------------------- |
| POST      | `/register`   | No   | Register an account and get a token |
| POST      | `/login`      | No   | Log in and get a token              |
| GET       | `/me`         | JWT  | Current authenticated user          |
| POST      | `/refresh`    | JWT  | Refresh the token                   |
| POST      | `/logout`     | JWT  | Invalidate the token                |
| GET       | `/users`      | JWT  | List users with their hobbies       |
| GET       | `/users/{id}` | JWT  | Show one user                       |
| POST      | `/users`      | JWT  | Create a user with hobbies          |
| PUT/PATCH | `/users/{id}` | JWT  | Update a user and their hobbies     |
| DELETE    | `/users/{id}` | JWT  | Delete a user and their hobbies     |

### Seeded account

The seeder creates an account you can use to log in to the API:

| Name        | Email              |
| ----------- | ------------------ |
| `Test User` | `test@example.com` |

### Example requests

**Login**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"[your password that has been set]"}'
```

Copy the `token` from the response:

```bash
TOKEN="paste-your-token-here"
```

**List users**

```bash
curl http://localhost:8000/api/users \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"
```

**Create a user with hobbies**

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Budi Santoso","email":"budi@example.com","password":"secret123","hobbies":["Football","Cooking"]}'
```

**Update a user (the hobbies list replaces the previous one)**

```bash
curl -X PUT http://localhost:8000/api/users/2 \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Budi S.","email":"budi@example.com","hobbies":["Football","Gaming"]}'
```

Leave `password` out (or empty) to keep the current password.

**Delete a user**

```bash
curl -X DELETE http://localhost:8000/api/users/2 \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"
```

### Error responses

| Status | Meaning                                                       |
| ------ | ------------------------------------------------------------- |
| 401    | Missing, invalid or expired token, or wrong login credentials |
| 404    | User not found                                                |
| 422    | Validation failed (the response lists the invalid fields)     |

## Database

- `users`: `id`, `name`, `email` (unique), `password`, timestamps
- `hobbies`: `id`, `user_id` (foreign key to `users`, cascade on delete), `name`, timestamps

Relation: `User hasMany Hobby`, `Hobby belongsTo User`.

## Design Notes

- **Service layer:** `UserService` wraps user and hobby writes in one `DB::transaction`, so a user is never saved without its hobbies if something fails. It is shared by the Blade and API controllers.
- **Hobby sync:** on update, the hobby list sent by the client replaces the existing one (hobbies not in the list are removed, new ones are added).
- **Passwords:** hashed automatically through the `hashed` cast on the `User` model.
- **Separation of concerns:** controllers only handle the request and response; validation is in Form Requests.