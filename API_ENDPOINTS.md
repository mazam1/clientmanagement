# API Endpoints Documentation

This document describes the RESTful API endpoints for the Clients and Sessions modules.

## Base URL

All API endpoints are prefixed with `/api/v1/`

## Authentication

No authentication is currently required (as per requirements). Security checks will be added in a future update.

---

## Clients API

### List All Clients
**GET** `/api/v1/clients`

Retrieve a paginated list of all clients with optional filtering and sorting.

**Query Parameters:**
- `search` (string, optional) - Search by name, email, or phone
- `status` (string, optional) - Filter by status: `active`, `inactive`, `archived`
- `sort_by` (string, optional, default: `created_at`) - Field to sort by
- `sort_direction` (string, optional, default: `desc`) - Sort direction: `asc` or `desc`
- `per_page` (integer, optional, default: 15) - Number of results per page

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "1234567890",
      "status": "active",
      "total_billable_hours": 25.5,
      "total_revenue": 1250.00,
      "sessions_count": 10,
      "invoices_count": 3,
      "created_at": "2024-01-15T10:00:00.000000Z",
      "updated_at": "2024-01-15T10:00:00.000000Z",
      "deleted_at": null
    }
  ],
  "links": {...},
  "meta": {...}
}
```

---

### Get Single Client
**GET** `/api/v1/clients/{id}`

Retrieve detailed information about a specific client.

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "status": "active",
    "total_billable_hours": 25.5,
    "total_revenue": 1250.00,
    "sessions_count": 10,
    "invoices_count": 3,
    "sessions": [...],
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T10:00:00.000000Z",
    "deleted_at": null
  }
}
```

---

### Create Client
**POST** `/api/v1/clients`

Create a new client.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "status": "active"
}
```

**Validation Rules:**
- `name` - Required, string, max 255 characters
- `email` - Optional, valid email, max 255 characters, unique
- `phone` - Optional, string, max 20 characters
- `status` - Required, one of: `active`, `inactive`, `archived`

**Response:** 201 Created
```json
{
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "status": "active",
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T10:00:00.000000Z"
  }
}
```

---

### Update Client
**PUT/PATCH** `/api/v1/clients/{id}`

Update an existing client.

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "email": "john.updated@example.com",
  "phone": "0987654321",
  "status": "inactive"
}
```

**Response:** 200 OK
```json
{
  "data": {
    "id": 1,
    "name": "John Doe Updated",
    "email": "john.updated@example.com",
    "phone": "0987654321",
    "status": "inactive",
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T15:30:00.000000Z"
  }
}
```

---

### Delete Client
**DELETE** `/api/v1/clients/{id}`

Soft delete a client.

**Response:** 200 OK
```json
{
  "message": "Client deleted successfully"
}
```

---

### Get Client Statistics
**GET** `/api/v1/clients-stats`

Retrieve statistics about clients.

**Response:**
```json
{
  "total": 50,
  "active": 35,
  "inactive": 10,
  "archived": 5
}
```

---

## Sessions API

### List All Sessions
**GET** `/api/v1/sessions`

Retrieve a paginated list of all sessions with optional filtering and sorting.

**Query Parameters:**
- `search` (string, optional) - Search by client name or notes
- `client_id` (integer, optional) - Filter by client
- `date_from` (date, optional) - Filter sessions from this date
- `date_to` (date, optional) - Filter sessions until this date
- `sort_by` (string, optional, default: `session_date`) - Field to sort by
- `sort_direction` (string, optional, default: `desc`) - Sort direction: `asc` or `desc`
- `per_page` (integer, optional, default: 15) - Number of results per page

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "client_id": 5,
      "client": {...},
      "session_date": "2024-01-15T14:00:00.000000Z",
      "duration_minutes": 120,
      "duration_hours": "2.00",
      "notes": "Initial consultation session",
      "created_at": "2024-01-10T10:00:00.000000Z",
      "updated_at": "2024-01-10T10:00:00.000000Z"
    }
  ],
  "links": {...},
  "meta": {...}
}
```

---

### Get Single Session
**GET** `/api/v1/sessions/{id}`

Retrieve detailed information about a specific session.

**Response:**
```json
{
  "data": {
    "id": 1,
    "client_id": 5,
    "client": {...},
    "session_date": "2024-01-15T14:00:00.000000Z",
    "duration_minutes": 120,
    "duration_hours": "2.00",
    "notes": "Initial consultation session",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  }
}
```

---

### Create Session
**POST** `/api/v1/sessions`

Create a new session.

**Request Body:**
```json
{
  "client_id": 5,
  "session_date": "2024-01-15 14:00:00",
  "duration_minutes": 120,
  "notes": "Initial consultation session"
}
```

**Validation Rules:**
- `client_id` - Required, must exist in clients table
- `session_date` - Required, valid datetime
- `duration_minutes` - Required, integer
- `notes` - Optional, string

**Response:** 201 Created
```json
{
  "data": {
    "id": 1,
    "client_id": 5,
    "session_date": "2024-01-15T14:00:00.000000Z",
    "duration_minutes": 120,
    "duration_hours": "2.00",
    "notes": "Initial consultation session",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  }
}
```

---

### Update Session
**PUT/PATCH** `/api/v1/sessions/{id}`

Update an existing session.

**Request Body:**
```json
{
  "client_id": 5,
  "session_date": "2024-01-15 15:00:00",
  "duration_minutes": 90,
  "notes": "Updated session notes"
}
```

**Response:** 200 OK
```json
{
  "data": {
    "id": 1,
    "client_id": 5,
    "session_date": "2024-01-15T15:00:00.000000Z",
    "duration_minutes": 90,
    "duration_hours": "1.50",
    "notes": "Updated session notes",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T12:00:00.000000Z"
  }
}
```

---

### Delete Session
**DELETE** `/api/v1/sessions/{id}`

Delete a session (hard delete).

**Response:** 200 OK
```json
{
  "message": "Session deleted successfully"
}
```

---

### Get Session Statistics
**GET** `/api/v1/sessions-stats`

Retrieve statistics about sessions.

**Response:**
```json
{
  "total_sessions": 150,
  "upcoming_sessions": 25,
  "total_hours": 320.5,
  "upcoming_hours": 45.0
}
```

---

### Get Upcoming Sessions
**GET** `/api/v1/sessions-upcoming`

Retrieve upcoming sessions within a specified time frame.

**Query Parameters:**
- `days` (integer, optional, default: 7) - Number of days ahead to look for sessions

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "client_id": 5,
      "client": {...},
      "session_date": "2024-01-20T14:00:00.000000Z",
      "duration_minutes": 60,
      "duration_hours": "1.00",
      "notes": "Follow-up session",
      "created_at": "2024-01-10T10:00:00.000000Z",
      "updated_at": "2024-01-10T10:00:00.000000Z"
    }
  ]
}
```

---

## Error Responses

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Client] 999"
}
```

### 422 Validation Error
```json
{
  "message": "The name field is required. (and 1 more error)",
  "errors": {
    "name": [
      "The name field is required."
    ],
    "status": [
      "The status field is required."
    ]
  }
}
```

### 500 Server Error
```json
{
  "message": "Server Error"
}
```

---

## Notes

- All timestamps are returned in ISO 8601 format (UTC)
- Pagination metadata includes: `current_page`, `last_page`, `per_page`, `total`, etc.
- The `client` relationship is eager loaded in session responses when requested
- The `sessions` relationship can be included in client responses
- Soft deleted clients are excluded from results unless specifically requested
- Sessions are permanently deleted (hard delete)
