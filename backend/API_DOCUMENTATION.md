# Authentication API Documentation

This Laravel application provides role-based authentication for two types of users: **Admin** and **Customer**.

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected routes require a Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

---

## Admin Endpoints

### 1. Admin Registration
**POST** `/admin/register`

Register a new admin user.

**Request Body:**
```json
{
    "name": "Admin Name",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "message": "Admin registered successfully",
    "user": {
        "id": 1,
        "name": "Admin Name",
        "email": "admin@example.com",
        "role": "admin",
        "created_at": "2025-10-16T20:31:51.000000Z",
        "updated_at": "2025-10-16T20:31:51.000000Z"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### 2. Admin Login
**POST** `/admin/login`

Login as an admin user.

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "message": "Admin logged in successfully",
    "user": {
        "id": 1,
        "name": "Admin Name",
        "email": "admin@example.com",
        "role": "admin"
    },
    "token": "2|xxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### 3. Admin Logout (Protected)
**POST** `/admin/logout`

Logout the authenticated admin user.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "message": "Admin logged out successfully"
}
```

### 4. Get Admin User (Protected)
**GET** `/admin/user`

Get the authenticated admin user details.

**Headers:**
```
Authorization: Bearer {admin-token}
```

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "name": "Admin Name",
        "email": "admin@example.com",
        "role": "admin"
    }
}
```

---

## Customer Endpoints

### 1. Customer Registration
**POST** `/customer/register`

Register a new customer user.

**Request Body:**
```json
{
    "name": "Customer Name",
    "email": "customer@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "message": "Customer registered successfully",
    "user": {
        "id": 2,
        "name": "Customer Name",
        "email": "customer@example.com",
        "role": "customer",
        "created_at": "2025-10-16T20:31:51.000000Z",
        "updated_at": "2025-10-16T20:31:51.000000Z"
    },
    "token": "3|xxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### 2. Customer Login
**POST** `/customer/login`

Login as a customer user.

**Request Body:**
```json
{
    "email": "customer@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "message": "Customer logged in successfully",
    "user": {
        "id": 2,
        "name": "Customer Name",
        "email": "customer@example.com",
        "role": "customer"
    },
    "token": "4|xxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### 3. Customer Logout (Protected)
**POST** `/customer/logout`

Logout the authenticated customer user.

**Headers:**
```
Authorization: Bearer {customer-token}
```

**Response (200):**
```json
{
    "message": "Customer logged out successfully"
}
```

### 4. Get Customer User (Protected)
**GET** `/customer/user`

Get the authenticated customer user details.

**Headers:**
```
Authorization: Bearer {customer-token}
```

**Response (200):**
```json
{
    "user": {
        "id": 2,
        "name": "Customer Name",
        "email": "customer@example.com",
        "role": "customer"
    }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "message": "The email has already been taken.",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

### Unauthorized (401)
```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
    "message": "Unauthorized. Admin access required."
}
```

### Invalid Credentials (422)
```json
{
    "message": "The provided credentials are incorrect.",
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

---

## Role-Based Access Control

- **Admin routes** (`/api/admin/*`) can only be accessed by users with `role = 'admin'`
- **Customer routes** (`/api/customer/*`) can only be accessed by users with `role = 'customer'`
- Attempting to access admin routes with a customer token (or vice versa) will result in a 403 Forbidden error

---

## Testing with cURL

### Register an Admin
```bash
curl -X POST http://localhost:8000/api/admin/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Admin User",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login as Admin
```bash
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

### Get Admin User (Protected)
```bash
curl -X GET http://localhost:8000/api/admin/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Database Schema

### Users Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | User's name |
| email | varchar(255) | User's email (unique) |
| password | varchar(255) | Hashed password |
| role | enum('admin', 'customer') | User role (default: 'customer') |
| email_verified_at | timestamp | Email verification timestamp |
| remember_token | varchar(100) | Remember token |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |
