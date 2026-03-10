# Immo API - Real Estate Platform

Laravel REST API for managing a real estate platform with role-based access control (admin, agent, guest).

## Features

- **User Management**: Registration, login, roles (admin, agent, guest)
- **Property Management**: Full CRUD with automatic title generation
- **Images**: Upload, delete images for each property
- **Advanced Filters**: By city, type, price, status
- **Search**: Text search on title and description
- **Pagination**: Paginated property listings

## Roles and Permissions

| Action                          | Admin | Agent | Guest |
|----------------------------------|-------|-------|-------|
| View properties                 | ✅    | ✅    | ✅    |
| Create property                 | ✅    | ✅    | ❌    |
| Edit own properties             | ✅    | ✅    | ❌    |
| Delete own properties           | ✅    | ✅    | ❌    |
| Edit all properties             | ✅    | ❌    | ❌    |
| Delete all properties           | ✅    | ❌    | ❌    |
| Upload images                   | ✅    | ✅    | ❌    |

## Installation

### Requirements

- PHP 8.2+
- Composer
- MySQL

### Installation Steps

```bash
# Install dependencies
composer install

# Copy .env file
cp .env.example .env

# MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=immo_api
DB_USERNAME=root
DB_PASSWORD=


# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database
# email:admin@admin.site / password:password
php artisan db:seed

# Start the server
php artisan serve
```

## API Usage Examples

### Registration

```http
POST /api/register
Content-Type: application/json

{
    "name": "Sadek Nadji",
    "email": "naji@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "agent",
    "phone": "+213555123456",
    "agency_name": "Real Estate Agency"
}
```

### Login

```http
POST /api/login
Content-Type: application/json

{
    "email": "naji@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Sadek Nadji",
            "email": "naji@example.com",
            "role": "agent"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### List Properties (with filters)

```http
GET /api/properties?city=Alger&type=villa&min_price=100000&max_price=500000&status=available&per_page=10
Authorization: Bearer {token}
```

### Get Property Details

```http
GET /api/properties/{id}
Authorization: Bearer {token}
```

### Create Property

```http
POST /api/properties
Authorization: Bearer {token}
Content-Type: application/json

{
    "type": "villa",
    "rooms": 4,
    "surface": 150.5,
    "price": 250000,
    "city": "Alger - Bab Ezzouar",
    "description": "Beautiful villa with garden...",
    "status": "available",
    "is_published": true
}
```

**The title is automatically generated:** `Villa 4 pièces à Alger - Bab Ezzouar`

### Update Property

```http
PUT /api/properties/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "price": 275000,
    "is_published": true
}
```

### Delete Property

```http
DELETE /api/properties/{id}
Authorization: Bearer {token}
```

### Upload Image

```http
POST /api/properties/{id}/images
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "image": (file)
}
```

### Toggle Publish Status

```http
POST /api/properties/{id}/toggle-publish
Authorization: Bearer {token}
```

### My Properties (Agent)

```http
GET /api/properties/my
Authorization: Bearer {token}
```

### Logout

```http
POST /api/logout
Authorization: Bearer {token}
```

### visit /api/documentation for more

### Data Flow

1. **Controller**: Receives HTTP request, validates with Form Request, calls Service
2. **Service**: Contains business logic, checks authorization (Gate/Policy), calls Repository
3. **Repository**: Handles all database interactions
4. **DTO**: Structures data between layers
5. **Resource**: Formats JSON response
