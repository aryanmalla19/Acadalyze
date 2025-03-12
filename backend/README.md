# API Documentation

## Authentication API

### Base URL
```
http://localhost:8080/
```

## Authorization
All endpoints require an Authorization token in the headers:
```
Authorization: Bearer <token>
```

## Endpoints

### 1. User Login
#### Endpoint
```
POST /api/auth/login
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
POST /api/auth/register
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
    "date_of_birth": "2000-01-01",
    "role": "Admin"
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
### **3. User Verification**

#### **Endpoint**
```
GET /api/auth/verify
```

#### **Description**
Verifies the authenticated user using the session cookies and returns a decoded JWT token.

#### **Authentication**
- **Required:** Yes (Cookies)
- **Method:** Cookie-based authentication (JWT stored in cookies)
- **Headers:** No additional headers required

#### **Request Body**
> 🚫 **Not required** – The request does not accept a body.

#### **Response (Success)**
```json
{
    "status": "success",
    "message": "Successfully verified! Authentication completed",
    "data": {
        "iat": 1,
        "exp": 1,
        "user_id": 1,
        "role": "Role",
        "identifier": "user@example.com"
    }
}
```

#### **Response (Error)**
**Invalid or Expired Token:**
```json
{
    "status": "error",
    "error": "Invalid or expired authentication token"
}
```

**Unauthorized (No Token Provided):**
```json
{
    "status": "error",
    "error": "Authentication required"
}
```

#### **Notes**
- This endpoint **only uses cookies** for authentication and does not accept a request body.
- The server expects the JWT token to be stored in the request's cookies.
- If the token is missing, expired, or invalid, an error response is returned.



### 4. User Logout
#### Endpoint
```
POST /api/auth/logout
```
#### Description
Logs out a user by removing jwt tojen from coojies.

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
    "message": "Logged out successfully"
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


### 5. Get All Users by School ID
**Endpoint:**
```
GET /api/users
```
**Description:** Retrieves all users associated with the authenticated user's school.

**Request Headers:**
```
Authorization: Bearer <token>
```

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "All Users data fetched successfully",
  "data": [ { "id": "1", "name": "John Doe", ... } ]
}
```
- **Error (404):**
```json
{
  "status": "error",
  "message": "No Users data found"
}
```

---

### 6. Get User by ID
**Endpoint:**
```
GET /api/users/{id}
```
**Description:** Retrieves user details by user ID.

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "User data fetched successfully",
  "data": { "id": "1", "name": "John Doe" }
}
```
- **Error (404):**
```json
{
  "status": "error",
  "message": "User with ID {id} does not exist",
  "data": []
}
```

---

### 7. Update User
**Endpoint:**
```
PUT /api/users/{id}
```
**Description:** Updates a user's information.

**Request Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:** (any field can be updated)
```json
{
  "email": "user@example.com",
  "username": "newUsername",
  "first_name": "John",
  "last_name": "Doe",
  "address": "123 Street",
  "phone_number": "1234567890"
}
```

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "User updated successfully",
  "data": { "email": "user@example.com" }
}
```
- **Validation Error (400):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "data": { "phone_number": "Invalid format" }
}
```
- **Conflict (409):**
```json
{
  "status": "error",
  "message": "Email already exists",
  "data": null
}
```

---

### 8. Delete User
**Endpoint:**
```
DELETE /users/{id}
```
**Description:** Deletes a user by ID.

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "User deleted successfully",
  "data": "1"
}
```
- **Error (404):**
```json
{
  "status": "error",
  "message": "Could not find User with ID {id}",
  "data": false
}
```

## Error Codes
| Code | Message                   |
|------|---------------------------|
| 400  | Validation failed         |
| 404  | Resource not found        |
| 409  | Conflict (duplicate data) |
| 500  | Internal server error     |




### Author: Aryan Malla
### Last Updated: 12 March 2025