# Assignment No. 9

## Product Inventory Management System using Spring Boot and MongoDB

This project is a Spring Boot REST API for managing product inventory data stored in MongoDB. It demonstrates MongoDB configuration, document mapping, a `MongoRepository`, CRUD APIs, Spring Security with Basic Authentication, and restricted access for selected endpoints.

## Tech Stack

- Java 21
- Spring Boot 3
- Spring Web
- Spring Data MongoDB
- Spring Security
- Maven
- MongoDB

## Project Structure

- `model/Product.java`: MongoDB document class
- `repository/ProductRepository.java`: `MongoRepository` interface
- `service/ProductService.java`: business logic for CRUD operations
- `controller/ProductController.java`: REST API endpoints
- `controller/AdminController.java`: restricted endpoint example
- `config/SecurityConfig.java`: Basic Authentication and authorization rules
- `exception/GlobalExceptionHandler.java`: centralized exception handling

## MongoDB Configuration

MongoDB connection is configured in `src/main/resources/application.properties`:

```properties
spring.data.mongodb.uri=mongodb://localhost:27017/product_inventory_db
```

Make sure MongoDB is running locally on port `27017`.

## Security Configuration

Basic Authentication is enabled using Spring Security.

### In-memory users

- Username: `inventoryuser`
  Password: `inventory123`
  Role: `USER`
- Username: `admin`
  Password: `admin123`
  Role: `ADMIN`

### Access Rules

- `GET /api/products` and `GET /api/products/{id}` are public
- `POST /api/products`, `PUT /api/products/{id}`, and `DELETE /api/products/{id}` require authentication
- `GET /api/admin/status` is restricted to the `ADMIN` role

## CRUD REST APIs

### 1. Get all products

- Method: `GET`
- URL: `http://localhost:8080/api/products`

### 2. Get product by ID

- Method: `GET`
- URL: `http://localhost:8080/api/products/{id}`

### 3. Create product

- Method: `POST`
- URL: `http://localhost:8080/api/products`
- Auth required: Yes

Sample request body:

```json
{
  "name": "Wireless Mouse",
  "category": "Electronics",
  "price": 799.00,
  "quantity": 50,
  "supplier": "LogiTech Suppliers"
}
```

### 4. Update product

- Method: `PUT`
- URL: `http://localhost:8080/api/products/{id}`
- Auth required: Yes

### 5. Delete product

- Method: `DELETE`
- URL: `http://localhost:8080/api/products/{id}`
- Auth required: Yes

## How to Run

1. Start MongoDB server.
2. Open a terminal in the project folder.
3. Run:

```bash
mvn spring-boot:run
```

The application will start on `http://localhost:8080`.

## Testing with Postman or Browser

### Browser

- Open `http://localhost:8080/api/products` to view all products
- Open `http://localhost:8080/api/products/{id}` to fetch a single product

### Postman

- Use `Basic Auth` for secured endpoints
- Username: `inventoryuser`
- Password: `inventory123`

Suggested test flow:

1. `GET /api/products`
2. `POST /api/products` with Basic Auth and JSON body
3. `GET /api/products`
4. `PUT /api/products/{id}` with Basic Auth
5. `DELETE /api/products/{id}` with Basic Auth
6. `GET /api/admin/status` using `admin/admin123`

## Expected Output

- Products are stored in MongoDB collection `products`
- Unauthorized requests to secured endpoints return `401 Unauthorized`
- Non-admin access to `/api/admin/status` returns `403 Forbidden`
