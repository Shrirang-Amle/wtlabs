# Task Manager REST API

A simple REST API built with Express.js to manage daily tasks.

## Setup

```bash
npm install
npm start          # production
npm run dev        # development (auto-reload with nodemon)
```

The server starts on **http://localhost:3000** by default.  
Set the `PORT` environment variable to override.

---

## API Endpoints

### Base URL: `/api/tasks`

| Method | Endpoint               | Description              |
|--------|------------------------|--------------------------|
| GET    | `/api/tasks`           | Retrieve all tasks       |
| GET    | `/api/tasks?status=`   | Filter by status         |
| GET    | `/api/tasks/:id`       | Retrieve a single task   |
| POST   | `/api/tasks`           | Create a new task        |
| PUT    | `/api/tasks/:id`       | Update task details      |
| PATCH  | `/api/tasks/:id/status`| Update task status only  |
| DELETE | `/api/tasks/:id`       | Delete a task            |

---

## Request & Response Examples

### Create a Task — `POST /api/tasks`

**Request body:**
```json
{
  "title": "Buy groceries",
  "description": "Milk, eggs, bread",
  "priority": "high"
}
```

**Response `201`:**
```json
{
  "success": true,
  "data": {
    "id": "a1b2c3d4-...",
    "title": "Buy groceries",
    "description": "Milk, eggs, bread",
    "priority": "high",
    "status": "pending",
    "createdAt": "2026-05-04T10:00:00.000Z",
    "updatedAt": "2026-05-04T10:00:00.000Z"
  }
}
```

### Get All Tasks — `GET /api/tasks`

Optional query param: `?status=pending` or `?status=completed`

**Response `200`:**
```json
{
  "success": true,
  "count": 1,
  "data": [ { ...task } ]
}
```

### Update Task Status — `PATCH /api/tasks/:id/status`

**Request body:**
```json
{ "status": "completed" }
```

### Update Task Details — `PUT /api/tasks/:id`

**Request body (all fields optional):**
```json
{
  "title": "Updated title",
  "description": "Updated description",
  "priority": "low"
}
```

### Delete a Task — `DELETE /api/tasks/:id`

**Response `200`:**
```json
{
  "success": true,
  "message": "Task deleted successfully",
  "data": { ...deletedTask }
}
```

---

## Task Schema

| Field       | Type   | Values                        |
|-------------|--------|-------------------------------|
| id          | string | UUID (auto-generated)         |
| title       | string | Required                      |
| description | string | Optional, default `""`        |
| priority    | string | `low` \| `medium` \| `high`   |
| status      | string | `pending` \| `completed`      |
| createdAt   | string | ISO 8601 timestamp            |
| updatedAt   | string | ISO 8601 timestamp            |
