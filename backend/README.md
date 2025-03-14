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
    "data": { ... }
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
    "role": "Admin",
    "school_id": "1(optional)"
}
```
#### Response (Success)
```json
{
    "status": "success",
    "message": "Registration successful",
    "data": { ... }
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


## 5. Get All Users

**Endpoint:**  
```
GET /api/users
```

**Description:**  
Retrieves all users associated with the authenticated user's school. You may optionally filter the users by providing a `role` query parameter (e.g., `role=admin`).

**Request Headers:**  
```
Authorization: Bearer <token>
```

**Cookies:**  
- `jwt_token` (Required): The authentication token must be present in cookies.

**Query Parameters:**  
- **role (optional):** Filter users by their role.

**Response:**

- **Success (200):**
```json
{
  "status": "success",
  "message": "All Users data fetched successfully",
  "data": [ { "id": "1", "name": "John Doe", ... } ]
}
```

- **Error (403):**
```json
{
  "status": "error",
  "message": "You are not associated with any school"
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


## 9. Get School by ID
**Endpoint:**
```
GET /api/schools/{id}
```
**Description:** Retrieves details of a school by its ID.

**Request Headers:**
```
Authorization: Bearer <token>
```
**Request Cookies:**
```
jwt=<token>
```

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "School data fetched successfully",
  "data": { "id": 1, "name": "ABC School", ... }
}
```
- **Error (404):**
```json
{
  "status": "error",
  "message": "School with ID {id} does not exist"
}
```

---

## 10. Get School by Authenticated User
**Endpoint:**
```
GET /api/schools/me
```
**Description:** Retrieves the school data of the authenticated user.

**Request Headers:**
```
Authorization: Bearer <token>
```
**Request Cookies:**
```
jwt=<token>
```

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "School data fetched successfully",
  "data": { "id": 1, "name": "ABC School", ... }
}
```
- **Error (404):**
```json
{
  "status": "error",
  "message": "You are not associated with any school"
}
```

---

## 11. Create a School
**Endpoint:**
```
POST /api/schools
```
**Description:** Registers a new school.

**Request Headers:**
```
Authorization: Bearer <token>
```
**Request Cookies:**
```
jwt=<token>
```

**Request Body:**
```json
{
  "school_email": "abc@school.com",
  "school_name": "ABC School",
  "established_date": "2000-01-01",
  "telephone_number": "1234567890",
  "address": "123 Street, City"
}
```

**Response:**
- **Success (201):**
```json
{
  "status": "success",
  "message": "Registration successful with School ID {id}",
  "data": null
}
```
- **Error (409):** School already exists
```json
{
  "status": "error",
  "message": "You are already associated with one school"
}
```
- **Error (409):** Duplicate email
```json
{
  "status": "error",
  "message": "School Email (abc@school.com) is already registered"
}
```

---

## 12. Update School Information
**Endpoint:**
```
PUT /api/schools/{id}
```
**Description:** Updates an existing school's information.

**Request Headers:**
```
Authorization: Bearer <token>
```
**Request Cookies:**
```
jwt=<token>
```

**Request Body:** (Any of these fields can be updated)
```json
{
  "school_email": "newemail@school.com",
  "school_name": "New School Name",
  "address": "456 New Street, City",
  "established_date": "1999-05-10",
  "telephone_number": "9876543210"
}
```

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "School updated successfully",
  "data": { ... }
}
```
- **Error (409):** Duplicate email
```json
{
  "status": "error",
  "message": "School Email already exists"
}
```
- **Error (400):** Invalid parameters
```json
{
  "status": "error",
  "message": "Incorrect params for school update",
  "data": {}
}
```

---

## 13. Delete School
**Endpoint:**
```
DELETE /api/schools/{id}
```
**Description:** Deletes a school by ID if no users are associated with it.

**Request Headers:**
```
Authorization: Bearer <token>
```
**Request Cookies:**
```
jwt=<token>
```

**Response:**
- **Success (200):**
```json
{
  "status": "success",
  "message": "Successfully deleted School ID {id}"
}
```
- **Error (400):** Users still associated
```json
{
  "status": "error",
  "message": "Cannot delete school ID {id}. There are users still associated with this school."
}
```
- **Error (404):** School not found
```json
{
  "status": "error",
  "message": "Not Found. Could not delete School ID {id}"
}
```


### 14. GET /api/classes
**Description:** Fetch all classes associated with the school of the authenticated admin.

**Response:**
```json
{
    "status": "success",
    "message": "All Classes successfully fetched",
    "data": [
        {
            "id": 1,
            "class_name": "Grade 10",
            "class_teacher_id": 5,
            "school_id": 2
        }
    ]
}
```

---

### 15. GET /api/classes/{id}
**Description:** Fetch a specific class by ID.

**Response:**
```json
{
    "status": "success",
    "message": "Class with ID {id} fetched",
    "data": {
        "id": 1,
        "class_name": "Grade 10",
        "class_teacher_id": 5,
        "school_id": 2
    }
}
```

**Error:**
```json
{
    "status": "error",
    "message": "Class with ID {id} not found",
    "data": null
}
```

---

### 16. POST /api/classes
**Description:** Create a new class. Only Admin can perform this action.

**Request Body:**
```json
{
    "class_teacher_id": 5,
    "school_id": 2,
    "class_name": "Grade 10"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "New Class Created with ID {newClassId}",
    "data": null
}
```

**Errors:**
- If teacher is not from the same school:
```json
{
    "status": "error",
    "message": "Cannot assign teacher of another school",
    "data": null
}
```

- If teacher is already assigned to another class:
```json
{
    "status": "error",
    "message": "Cannot assign teacher that is already associated with another class",
    "data": null
}
```

---

### 17. PUT /api/classes/{id}
**Description:** Update class details by ID.

**Request Body (Partial Updates Allowed):**
```json
{
    "class_teacher_id": 7
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Class updated successfully",
    "data": {
        "class_teacher_id": 7
    }
}
```

**Errors:**
- If trying to change school ID:
```json
{
    "status": "error",
    "message": "Cannot Change School ID",
    "data": null
}
```
- If teacher belongs to another school:
```json
{
    "status": "error",
    "message": "Cannot Assign Teacher of Another School",
    "data": null
}
```

---

### 18. DELETE /api/classes/{id}
**Description:** Delete a class by ID.

**Response:**
```json
{
    "status": "success",
    "message": "Successfully Deleted Class with ID {id}",
    "data": {id}
}
```

**Error:**
```json
{
    "status": "error",
    "message": "Class with ID {id} not found",
    "data": []
}
```


### 19. Get All Subjects
**Endpoint:** `GET /api/subjects`

**Description:** Fetch all subjects associated with the school of the authenticated user.

**Authorization:** Requires authentication (Admin, Teacher)

**Query Parameters (optional):**
- `class_id` (integer) - Fetch subjects specific to a class

**Response:**
- `200 OK` - Returns a list of subjects
- `403 Forbidden` - User not associated with any school
- `404 Not Found` - No subjects found
---

### 20. Get a Specific Subject
**Endpoint:** `GET /api/subjects/{id}`

**Description:** Fetch details of a specific subject by ID.

**Authorization:** Requires authentication with access policy.

**Path Parameters:**
- `id` (integer) - ID of the subject

**Response:**
- `200 OK` - Returns subject details
- `404 Not Found` - Subject not found
---

### 21. Create a Subject
**Endpoint:** `POST /api/subjects`

**Description:** Create a new subject and assign it to a class and teacher.

**Authorization:** Requires authentication (Admin, Teacher)

**Request Body:**
```json
{
  "class_id": "integer (required)",
  "teacher_id": "integer (required)",
  "subject_name": "string (required, 2-20 characters)"
}
```

**Response:**
- `201 Created` - Subject created successfully
- `400 Bad Request` - Validation errors
- `500 Internal Server Error` - Unexpected error

---

### 22. Update a Subject
**Endpoint:** `PUT /api/subjects/{id}`

**Description:** Update an existing subject's details.

**Authorization:** Requires authentication (Admin, Teacher) with access policy.

**Path Parameters:**
- `id` (integer) - ID of the subject

**Request Body:** (only include fields to update)
```json
{
  "class_id": "integer (optional)",
  "teacher_id": "integer (optional)",
  "subject_name": "string (optional, 2-30 characters)"
}
```

**Response:**
- `200 OK` - Subject updated successfully
- `400 Bad Request` - Invalid data
- `404 Not Found` - Subject not found
- `409 Conflict` - Duplicate entry detected
- `500 Internal Server Error` - Database error

---

### 23. Delete a Subject
**Endpoint:** `DELETE /api/subjects/{id}`

**Description:** Delete a specific subject by ID.

**Authorization:** Requires authentication (Admin, Teacher) with access policy.

**Path Parameters:**
- `id` (integer) - ID of the subject

**Response:**
- `200 OK` - Subject deleted successfully
- `404 Not Found` - Subject does not exist


### 24 GET /api/exams
**Description:** Retrieve all exam records associated with the authenticated user's school.

**Middleware:**  
- AuthMiddleware  
- RoleMiddleware (Admin, Teacher)

**Example Request:**
```json
GET /api/exams
```

**Example Response:**
```json
{
  "status": "success",
  "message": "All Exams fetched successfully",
  "data": [
    {
      "id": 1,
      "school_id": "123",
      "exam_name": "Midterm Exam",
      "exam_date": "2025-03-20"
    }
  ]
}
```

**Response Codes:**  
- 200: Exams fetched successfully  
- 404: Exam data not found

---

### 25 GET /api/exams/{id}
**Description:** Retrieve a specific exam record by its ID.

**Middleware:**  
- AuthMiddleware  
- AccessMiddleware (ExamPolicy: view)

**Example Request:**
```json
GET /api/exams/1
```

**Example Response:**
```json
{
  "status": "success",
  "message": "Exam data fetched successfully",
  "data": {
    "id": 1,
    "school_id": "123",
    "exam_name": "Midterm Exam",
    "exam_date": "2025-03-20"
  }
}
```

**Response Codes:**  
- 200: Exam data fetched successfully  
- 404: Exam data not found

---

### 26 POST /api/exams
**Description:** Create a new exam record.

**Request Body:**
```json
{
  "school_id": "123",
  "exam_name": "Final Exam",
  "exam_date": "2025-06-15"
}
```

**Middleware:**  
- AuthMiddleware  
- RoleMiddleware (Admin, Teacher)

**Example Response:**
```json
{
  "status": "success",
  "message": "Registration successful with School ID 5",
  "data": null
}
```

**Response Codes:**  
- 200: Registration successful  
- 400: Validation errors  
- 500: Internal Server Error

---

### 27 PUT /api/exams/{id}
**Description:** Update an existing exam record by its ID.

**Request Body:**
```json
{
  "school_id": "123",
  "exam_name": "Updated Exam Name",
  "exam_date": "2025-04-01"
}
```

**Middleware:**  
- AuthMiddleware  
- RoleMiddleware (Admin, Teacher)  
- AccessMiddleware (ExamPolicy: update)

**Example Response:**
```json
{
  "status": "success",
  "message": "Exam with ID 1 updated successfully",
  "data": {
    "school_id": "123",
    "exam_name": "Updated Exam Name",
    "exam_date": "2025-04-01"
  }
}
```

**Response Codes:**  
- 200: Exam updated successfully  
- 400: Validation errors or invalid data  
- 404: No data provided to update or Exam not found  
- 409: Duplicate entry detected  
- 500: Internal Server Error

---

### 28 DELETE /api/exams/{id}
**Description:** Delete an exam record by its ID.

**Middleware:**  
- AuthMiddleware  
- RoleMiddleware (Admin, Teacher)  
- AccessMiddleware (ExamPolicy: view)

**Example Request:**
```json
DELETE /api/exams/1
```

**Example Response:**
```json
{
  "status": "success",
  "message": "Successfully deleted Exam with ID 1",
  "data": null
}
```

**Response Codes:**  
- 200: Exam deleted successfully  
- 404: Exam not found

---

## 29. Get All Subject Exams

### Endpoint
```http
GET /api/subject-exams
```

### Headers
```json
{
  "Authorization": "Bearer YOUR_ACCESS_TOKEN"
}
```

### Response
```json
{
  "status": "success",
  "message": "All Subjects_Exams fetched successfully",
  "data": [
    {
      "id": 1,
      "subject_id": 101,
      "exam_id": 202,
      "subject_exam_time": "10:00 AM",
      "pass_marks": 40,
      "full_marks": 100
    }
  ]
}
```

## 30. Get a Specific Subject Exam

### Endpoint
```http
GET /api/subject-exams/{id}
```

### Headers
```json
{
  "Authorization": "Bearer YOUR_ACCESS_TOKEN"
}
```

### Response
```json
{
  "status": "success",
  "message": "Subject_Exam data fetched successfully",
  "data": {
    "id": 1,
    "subject_id": 101,
    "exam_id": 202,
    "subject_exam_time": "10:00 AM",
    "pass_marks": 40,
    "full_marks": 100
  }
}
```

## 31. Create a Subject Exam

### Endpoint
```http
POST /api/subject-exams
```

### Headers
```json
{
  "Authorization": "Bearer YOUR_ACCESS_TOKEN",
  "Content-Type": "application/json"
}
```

### Body
```json
{
  "subject_id": 101,
  "exam_id": 202,
  "subject_exam_time": "10:00 AM",
  "pass_marks": 40,
  "full_marks": 100
}
```

### Response
```json
{
  "status": "success",
  "message": "Registration successful with Subjects_Exam ID 1"
}
```

## 32. Update a Subject Exam

### Endpoint
```http
PUT /api/subject-exams/{id}
```

### Headers
```json
{
  "Authorization": "Bearer YOUR_ACCESS_TOKEN",
  "Content-Type": "application/json"
}
```

### Body
```json
{
  "subject_exam_time": "11:00 AM",
  "pass_marks": 45
}
```

### Response
```json
{
  "status": "success",
  "message": "Subject_Exam with ID 1 updated successfully",
  "data": {
    "subject_exam_time": "11:00 AM",
    "pass_marks": 45
  }
}
```

## 33. Delete a Subject Exam

### Endpoint
```http
DELETE /api/subject-exams/{id}
```

### Headers
```json
{
  "Authorization": "Bearer YOUR_ACCESS_TOKEN"
}
```

### Response
```json
{
  "status": "success",
  "message": "Subject_Exam data deleted successfully"
}
```

### 34. Get All Marks
**Endpoint:** `GET /api/marks`

**Middleware:**
- `AuthMiddleware`
- `RoleMiddleware` (Admin, Teacher)

**Response:**
```json
{
  "status": "success",
  "message": "Marks data fetched successfully",
  "data": [
    { "id": 1, "student_id": 5, "subjects_exams_id": 10, "marks_obtained": 85 },
    { "id": 2, "student_id": 8, "subjects_exams_id": 12, "marks_obtained": 92 }
  ]
}
```

---

### 35. Get Marks by ID
**Endpoint:** `GET /api/marks/{id}`

**Middleware:**
- `AuthMiddleware`
- `AccessMiddleware` (MarksPolicy - View)

**Response:**
```json
{
  "status": "success",
  "message": "Marks data with ID {id} fetched successfully",
  "data": { "id": 1, "student_id": 5, "subjects_exams_id": 10, "marks_obtained": 85 }
}
```

---

### 36. Create Marks
**Endpoint:** `POST /api/marks`

**Middleware:**
- `AuthMiddleware`
- `RoleMiddleware` (Admin, Teacher)

**Request Body:**
```json
{
  "student_id": 5,
  "subjects_exams_id": 10,
  "marks_obtained": 85
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Successfully created Marks with ID {newMarksID}"
}
```

---

### 37. Update Marks
**Endpoint:** `PUT /api/marks/{id}`

**Middleware:**
- `AuthMiddleware`
- `RoleMiddleware` (Admin, Teacher)
- `AccessMiddleware` (MarksPolicy - Update)

**Request Body:**
```json
{
  "student_id": 5,
  "subjects_exams_id": 10,
  "marks_obtained": 90
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Marks data with ID {id} updated successfully",
  "data": { "student_id": 5, "subjects_exams_id": 10, "marks_obtained": 90 }
}
```

---

### 38. Delete Marks
**Endpoint:** `DELETE /api/marks/{id}`

**Middleware:**
- `AuthMiddleware`
- `RoleMiddleware` (Admin, Teacher)
- `AccessMiddleware` (MarksPolicy - View)

**Response:**
```json
{
  "status": "success",
  "message": "Successfully deleted Marks with ID {id}"
}
```

### 39. Get All Attendance Records
**Method:** GET  
**Endpoint:** `/api/attendance`  
**Description:** Fetch all attendance records with optional filters.

#### Query Parameters:
- `student_id` (optional): Filter by student ID.
- `class_id` (optional): Filter by class ID.
- `start_date` (optional): Start date for filtering (YYYY-MM-DD).
- `end_date` (optional): End date for filtering (YYYY-MM-DD).

#### Middleware:
- `AuthMiddleware`
- `RoleMiddleware` (Allowed: Admin, Teacher)

#### Example Response:
```json
{
  "status": "success",
  "message": "Attendance data fetched successfully",
  "data": [
    {
      "id": 1,
      "student_id": 101,
      "class_id": 5,
      "attendance_date": "2025-03-10",
      "status": "Present"
    }
  ]
}
```

---

### 40. Get Single Attendance Record
**Method:** GET  
**Endpoint:** `/api/attendance/{id}`  
**Description:** Fetch a single attendance record by ID.

#### Middleware:
- `AuthMiddleware`
- `AccessMiddleware` (Policy: `AttendancePolicy`, Action: `view`)

#### Example Response:
```json
{
  "status": "success",
  "message": "Attendance data with ID 1 fetched successfully",
  "data": {
    "id": 1,
    "student_id": 101,
    "class_id": 5,
    "attendance_date": "2025-03-10",
    "status": "Present"
  }
}
```

---

### 41. Create Attendance Record
**Method:** POST  
**Endpoint:** `/api/attendance`  
**Description:** Create a new attendance record.

#### Request Body:
```json
{
  "student_id": 101,
  "class_id": 5,
  "attendance_date": "2025-03-10",
  "status": "Present"
}
```

#### Middleware:
- `AuthMiddleware`
- `RoleMiddleware` (Allowed: Admin, Teacher)

#### Example Response:
```json
{
  "status": "success",
  "message": "Successfully created attendance record",
  "attendance_id": 201
}
```

---

### 42. Update Attendance Record
**Method:** PUT  
**Endpoint:** `/api/attendance/{id}`  
**Description:** Update an existing attendance record.

#### Request Body:
```json
{
  "status": "Absent"
}
```

#### Middleware:
- `AuthMiddleware`
- `RoleMiddleware` (Allowed: Admin, Teacher)
- `AccessMiddleware` (Policy: `AttendancePolicy`, Action: `update`)

#### Example Response:
```json
{
  "status": "success",
  "message": "Attendance data with ID 1 updated successfully"
}
```

---

### 43. Delete Attendance Record
**Method:** DELETE  
**Endpoint:** `/api/attendance/{id}`  
**Description:** Delete an attendance record by ID.

#### Middleware:
- `AuthMiddleware`
- `RoleMiddleware` (Allowed: Admin, Teacher)
- `AccessMiddleware` (Policy: `AttendancePolicy`, Action: `view`)

#### Example Response:
```json
{
  "status": "success",
  "message": "Successfully deleted attendance with ID 1"
}
```

---

## Error Responses
For any request failure, the API returns an error response.

#### Example:
```json
{
  "status": "error",
  "message": "Attendance data not found.",
  "data": []
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
### Last Updated: 14 March 2025