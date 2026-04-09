# Ethical Marketplace API

A personal PHP REST API project for an ethical marketplace concept. This backend focuses on vendor management, product discovery, and order tracking.

## Features
- Vendor registration and login with JWT authentication
- CRUD operations for vendors (small business profiles)
- CRUD operations for products
- Search and filtering by category, location, and sustainability rating
- Order creation and vendor-side status updates (`pending` → `processing` → `shipped` → `delivered`)
- MySQL database schema and seed data
- Clean JSON responses and simple routing without a framework

## Tech Stack
- PHP 8+
- MySQL
- REST API
- JWT (implemented in pure PHP)

## Project Structure
```
ethical-marketplace-api/
├── config/
├── core/
│   ├── controllers/
│   ├── middleware/
│   ├── models/
│   └── utils/
├── database/
├── docs/
├── public/
└── README.md
```

## Setup
1. Create a MySQL database named `ethical_marketplace`.
2. Import `database/schema.sql`.
3. Optionally import `database/seed.sql`.
4. Update `config/config.php` with your database credentials.
5. Start the PHP built-in server from the project root:
   ```bash
   php -S localhost:8000 -t public
   ```
6. Test the API with Postman or Insomnia.

## Default Vendor Login (from seed)
- Email: `vendor@example.com`
- Password: `password123`

## Main Endpoints
### Auth
- `POST /api/register`
- `POST /api/login`
- `GET /api/me`

### Vendors
- `GET /api/vendors`
- `GET /api/vendors/{id}`
- `POST /api/vendors`
- `PUT /api/vendors/{id}`
- `DELETE /api/vendors/{id}`

### Products
- `GET /api/products`
- `GET /api/products/{id}`
- `POST /api/products`
- `PUT /api/products/{id}`
- `DELETE /api/products/{id}`

Query parameters for product listing:
- `category`
- `location`
- `min_rating`
- `search`

### Orders
- `GET /api/orders`
- `GET /api/orders/{id}`
- `POST /api/orders`
- `PUT /api/orders/{id}/status`

## Example Login Request
```http
POST /api/login
Content-Type: application/json

{
  "email": "vendor@example.com",
  "password": "password123"
}
```

## Example Login Response
```json
{
  "status": "success",
  "message": "Login successful.",
  "data": {
    "token": "your-jwt-token"
  }
}
```

## Notes
- Protected routes require an `Authorization: Bearer <token>` header.
- Vendors can only update their own profiles, products, and order statuses.
- This project is designed to be simple, readable, and portfolio-friendly.
# ethical_marketplace
