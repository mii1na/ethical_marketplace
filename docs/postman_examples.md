# Postman Examples

## Register Vendor
**POST** `/api/register`
```json
{
  "business_name": "Eco Threads",
  "owner_name": "Mina Fahim",
  "email": "eco@example.com",
  "password": "secret123",
  "category": "Fashion",
  "location": "Toronto",
  "sustainability_rating": 4.9,
  "description": "Sustainable handmade fashion brand."
}
```

## Login
**POST** `/api/login`
```json
{
  "email": "vendor@example.com",
  "password": "password123"
}
```

## Create Product
**POST** `/api/products`
Header: `Authorization: Bearer YOUR_TOKEN`
```json
{
  "name": "Refillable Glass Bottle",
  "category": "Kitchen",
  "price": 24.99,
  "stock": 30,
  "sustainability_rating": 4.8,
  "description": "Reusable bottle for everyday use."
}
```

## Search Products
**GET** `/api/products?category=Kitchen&location=Toronto&min_rating=4.5&search=bottle`

## Create Order
**POST** `/api/orders`
```json
{
  "product_id": 1,
  "customer_name": "Ava Wilson",
  "customer_email": "ava@example.com",
  "quantity": 2
}
```

## Update Order Status
**PUT** `/api/orders/1/status`
Header: `Authorization: Bearer YOUR_TOKEN`
```json
{
  "status": "shipped"
}
```
