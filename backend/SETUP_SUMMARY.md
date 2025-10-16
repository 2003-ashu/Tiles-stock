# Laravel Authentication Setup Summary

## ✅ Completed Setup

### 1. Database Migration
- **Migration File**: `2025_10_16_203151_add_role_to_users_table.php`
- Added `role` column to users table with enum values: `admin`, `customer`
- Default role set to `customer`

### 2. User Model Updates
- **File**: `app/Models/User.php`
- Added `role` to `$fillable` array
- Added `HasApiTokens` trait for Laravel Sanctum
- Helper methods:
  - `isAdmin()` - Check if user is admin
  - `isCustomer()` - Check if user is customer

### 3. Authentication Controllers

#### AdminAuthController
**File**: `app/Http/Controllers/Auth/AdminAuthController.php`

Methods:
- `register()` - Register new admin user
- `login()` - Admin login (checks for admin role)
- `logout()` - Admin logout
- `user()` - Get authenticated admin user

#### CustomerAuthController
**File**: `app/Http/Controllers/Auth/CustomerAuthController.php`

Methods:
- `register()` - Register new customer user
- `login()` - Customer login (checks for customer role)
- `logout()` - Customer logout
- `user()` - Get authenticated customer user

### 4. Middleware

#### AdminMiddleware
**File**: `app/Http/Middleware/AdminMiddleware.php`
- Protects admin routes
- Returns 403 if user is not admin

#### CustomerMiddleware
**File**: `app/Http/Middleware/CustomerMiddleware.php`
- Protects customer routes
- Returns 403 if user is not customer

### 5. API Routes
**File**: `routes/api.php`

#### Admin Routes (prefix: `/api/admin`)
- `POST /api/admin/register` - Public
- `POST /api/admin/login` - Public
- `POST /api/admin/logout` - Protected (auth:sanctum, admin)
- `GET /api/admin/user` - Protected (auth:sanctum, admin)

#### Customer Routes (prefix: `/api/customer`)
- `POST /api/customer/register` - Public
- `POST /api/customer/login` - Public
- `POST /api/customer/logout` - Protected (auth:sanctum, customer)
- `GET /api/customer/user` - Protected (auth:sanctum, customer)

### 6. Laravel Sanctum
- Installed and configured Laravel Sanctum v4.2.0
- API token authentication enabled
- Personal access tokens table migrated

### 7. Bootstrap Configuration
**File**: `bootstrap/app.php`
- API routes registered
- Middleware aliases registered:
  - `admin` → `AdminMiddleware`
  - `customer` → `CustomerMiddleware`

## 📁 File Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auth/
│   │   │       ├── AdminAuthController.php
│   │   │       └── CustomerAuthController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── CustomerMiddleware.php
│   └── Models/
│       └── User.php (updated with role logic)
├── bootstrap/
│   └── app.php (updated with API routes and middleware)
├── database/
│   └── migrations/
│       └── 2025_10_16_203151_add_role_to_users_table.php
├── routes/
│   └── api.php (new file with auth routes)
├── API_DOCUMENTATION.md (complete API docs)
└── SETUP_SUMMARY.md (this file)
```

## 🚀 How to Start the Server

```bash
cd backend
php artisan serve
```

The API will be available at: `http://localhost:8000/api`

## 🧪 Quick Test

### Register Admin:
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

### Register Customer:
```bash
curl -X POST http://localhost:8000/api/customer/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Customer User",
    "email": "customer@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

## 🔐 Security Features

1. **Password Hashing**: All passwords are hashed using Laravel's Hash facade
2. **Token-based Authentication**: Using Laravel Sanctum for API authentication
3. **Role-based Access Control**: Separate middleware for admin and customer routes
4. **Request Validation**: All inputs are validated before processing
5. **Unique Email Constraint**: Email addresses must be unique across all users

## 📝 Notes

- Admin users can only access `/api/admin/*` routes
- Customer users can only access `/api/customer/*` routes
- Cross-role access is blocked by middleware (403 Forbidden)
- Tokens are automatically generated on login/register
- Logout deletes the current access token
