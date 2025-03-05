# API Documentation

## Authentication API

### Base URL
```
http://localhost:8080/
```

## Endpoints

### 1. User Login
#### Endpoint
```
POST /api/login
```
#### Description
Authenticates a user and returns a JWT token.

#### Request Body (JSON)
```json
{
    "identifier": "user@example.com",
    "password": "yourpassword"
}
```
#### Response (Success)
```json
{
    "status": "success",
    "message": "Login successful",
    "token": "your-jwt-token"
}
```
#### Response (Error)
```json
{
    "status": "error",
    "error": "Invalid credentials"
}
```

---

### 2. User Registration
#### Endpoint
```
POST /api/register
```
#### Description
Registers a new user.

#### Request Body (JSON)
```json
{
    "email": "user@example.com",
    "password": "yourpassword",
    "username": "username",
    "first_name": "John",
    "last_name": "Doe",
    "address": "123 Street",
    "phone_number": "1234567890",
    "parent_phone_number": "0987654321",
    "date_of_birth": "2000-01-01"
}
```
#### Response (Success)
```json
{
    "message": "Registration successful",
    "user": true
}
```
#### Response (Error)
```json
{
    "status": "error",
    "error": "Email is already registered"
}
```
```json
{
    "status": "error",
    "error": "Username is already registered"
}
```
