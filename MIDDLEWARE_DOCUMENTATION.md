# Middleware Documentation

## Overview

This document describes all middleware used to protect routes based on user roles in the application.

---

## Role-Based Middlewares

### 1. `isAdmin` Middleware
**File**: `app/Http/Middleware/isAdmin.php`  
**Purpose**: Protects routes accessible only to Administrators (role 0)

**Usage**:
```php
Route::middleware(['auth', 'isAdmin'])->group(function () {
    // Admin-only routes
});
```

**Behavior**:
- Checks if user is authenticated
- Verifies user role is 0 (Admin)
- Returns 403 if user is not an admin

**Used in**:
- `routes/admin.php` - All admin routes (categories, users, team-members)

---

### 2. `isDesigner` Middleware
**File**: `app/Http/Middleware/IsDesigner.php`  
**Purpose**: Protects routes accessible only to Designers (role 1)

**Usage**:
```php
Route::middleware(['auth', 'isDesigner'])->group(function () {
    // Designer-only routes
});
```

**Behavior**:
- Checks if user is authenticated
- Verifies user role is 1 (Designer)
- Returns 401 if not authenticated
- Returns 403 if user is not a designer

**Used in**:
- `routes/designer.php` - All designer routes (dashboard, products, profile)

---

### 3. `isConstructor` Middleware
**File**: `app/Http/Middleware/IsConstructor.php`  
**Purpose**: Protects routes accessible only to Constructors (role 3)

**Usage**:
```php
Route::middleware(['auth', 'isConstructor'])->group(function () {
    // Constructor-only routes
});
```

**Behavior**:
- Checks if user is authenticated
- Verifies user role is 3 (Constructor)
- Returns 401 if not authenticated
- Returns 403 if user is not a constructor

**Used in**:
- `routes/constructor.php` - All constructor routes (dashboard, products, profile)

---

### 4. `isCustomer` Middleware
**File**: `app/Http/Middleware/IsCustomer.php`  
**Purpose**: Protects routes accessible only to Customers (role 2)

**Usage**:
```php
Route::middleware(['auth', 'isCustomer'])->group(function () {
    // Customer-only routes
});
```

**Behavior**:
- Checks if user is authenticated
- Verifies user role is 2 (Customer)
- Returns 401 if not authenticated
- Returns 403 if user is not a customer

**Used in**:
- `routes/customer.php` - All customer routes (dashboard, orders, cart, profile)

---

## Feature-Based Middlewares

### 5. `canManageProducts` Middleware
**File**: `app/Http/Middleware/CanManageProducts.php`  
**Purpose**: Allows product management for Admin, Designer, and Constructor roles (blocks Customer)

**Usage**:
```php
Route::middleware(['auth', 'canManageProducts'])->group(function () {
    // Product management routes
});
```

**Behavior**:
- Checks if user is authenticated
- Allows roles: 0 (Admin), 1 (Designer), 3 (Constructor)
- Blocks role: 2 (Customer)
- Returns 403 if user cannot manage products

**Used in**:
- `routes/admin.php` - Product management routes for admin

**Note**: This middleware is used for shared product management routes that multiple roles can access.

---

### 6. `CheckRole` Middleware (Generic)
**File**: `app/Http/Middleware/CheckRole.php`  
**Purpose**: Generic role checker that accepts role as parameter

**Usage**:
```php
Route::middleware(['auth', 'role:1'])->group(function () {
    // Routes for role 1
});
```

**Behavior**:
- Checks if user is authenticated
- Verifies user role matches the provided parameter
- Returns 401 if not authenticated
- Returns 403 if role doesn't match

**Note**: This middleware is still available but it's recommended to use specific role middlewares (`isDesigner`, `isConstructor`, etc.) for better code clarity.

---

## Middleware Registration

All middlewares are registered in `bootstrap/app.php`:

```php
$middleware->alias([
    'isAdmin' => isAdmin::class,
    'canManageProducts' => CanManageProducts::class,
    'role' => CheckRole::class,
    'isDesigner' => IsDesigner::class,
    'isConstructor' => IsConstructor::class,
    'isCustomer' => IsCustomer::class,
]);
```

---

## Route Protection Summary

### Admin Routes (`routes/admin.php`)
- **Middleware**: `['auth', 'isAdmin']`
- **Protected Features**: Categories, Users, Team Members
- **Product Routes**: `['auth', 'canManageProducts']`

### Designer Routes (`routes/designer.php`)
- **Middleware**: `['auth', 'isDesigner']`
- **Protected Features**: Dashboard, Products (own), Profile

### Constructor Routes (`routes/constructor.php`)
- **Middleware**: `['auth', 'isConstructor']`
- **Protected Features**: Dashboard, Products (own), Profile

### Customer Routes (`routes/customer.php`)
- **Middleware**: `['auth', 'isCustomer']`
- **Protected Features**: Dashboard, Orders, Cart, Profile

---

## User Roles Reference

| Role ID | Role Name | Middleware |
|---------|-----------|------------|
| 0 | Admin | `isAdmin` |
| 1 | Designer | `isDesigner` |
| 2 | Customer | `isCustomer` |
| 3 | Constructor | `isConstructor` |

---

## Security Best Practices

1. **Always use `auth` middleware first**: Ensures user is authenticated before checking role
2. **Use specific role middlewares**: More explicit and easier to maintain than generic `role:X`
3. **Combine with feature middlewares**: Use `canManageProducts` for shared features
4. **Consistent error messages**: All middlewares return appropriate HTTP status codes (401/403)

---

## Testing Middlewares

To test middleware protection:

1. **As Admin**: Should access all admin routes
2. **As Designer**: Should only access designer routes, blocked from admin/constructor/customer routes
3. **As Constructor**: Should only access constructor routes, blocked from admin/designer/customer routes
4. **As Customer**: Should only access customer routes, blocked from admin/designer/constructor routes

---

## Error Responses

All middlewares return appropriate HTTP status codes:

- **401 Unauthorized**: User is not authenticated
- **403 Forbidden**: User is authenticated but doesn't have the required role

Error messages are descriptive and help identify the issue:
- "Unauthenticated." - User not logged in
- "Unauthorized action. This route is only accessible to [Role]." - Wrong role
- "Unauthorized action. Only admins, designers, and constructors can manage products." - Feature restriction

---

## Migration from Generic `role` Middleware

**Before**:
```php
Route::middleware(['auth', 'role:1'])->group(function () {
    // Designer routes
});
```

**After** (Recommended):
```php
Route::middleware(['auth', 'isDesigner'])->group(function () {
    // Designer routes
});
```

**Benefits**:
- More readable and self-documenting
- Easier to find all routes for a specific role
- Better IDE autocomplete support
- Clearer error messages

---

## Files Created/Modified

### New Middleware Files
1. `app/Http/Middleware/IsDesigner.php`
2. `app/Http/Middleware/IsConstructor.php`
3. `app/Http/Middleware/IsCustomer.php`

### Modified Files
1. `bootstrap/app.php` - Added middleware aliases
2. `routes/designer.php` - Changed from `role:1` to `isDesigner`
3. `routes/constructor.php` - Changed from `role:3` to `isConstructor`
4. `routes/customer.php` - Added `isCustomer` middleware

---

## Summary

All routes are now protected with role-specific middlewares:
- ✅ Admin routes protected with `isAdmin`
- ✅ Designer routes protected with `isDesigner`
- ✅ Constructor routes protected with `isConstructor`
- ✅ Customer routes protected with `isCustomer`
- ✅ Product management routes protected with `canManageProducts`

This ensures proper access control and security for all user roles in the application.
