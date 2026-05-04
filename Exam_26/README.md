# Order Management System

Spring Boot-based Order Management System with REST APIs to create, view, update, and delete customer orders.

## Tech Stack

- Spring Boot 3
- Spring Web
- Spring Data JPA
- H2 Database
- Maven

## Order Entity Fields

- `id`
- `customerName`
- `productName`
- `quantity`
- `price`
- `status`

## REST APIs

### Create Order

- Method: `POST`
- URL: `http://localhost:8080/api/orders`

```json
{
  "customerName": "Shrirang",
  "productName": "Laptop",
  "quantity": 2,
  "price": 55000.00,
  "status": "CREATED"
}
```

### Get All Orders

- Method: `GET`
- URL: `http://localhost:8080/api/orders`

### Get Order By ID

- Method: `GET`
- URL: `http://localhost:8080/api/orders/1`

### Update Order

- Method: `PUT`
- URL: `http://localhost:8080/api/orders/1`

```json
{
  "customerName": "Shrirang",
  "productName": "Gaming Laptop",
  "quantity": 2,
  "price": 55000.00,
  "status": "SHIPPED"
}
```

### Delete Order

- Method: `DELETE`
- URL: `http://localhost:8080/api/orders/1`

## Run the Project

```bash
mvn spring-boot:run
```

## Test with Postman or REST Client

1. Start the application using `mvn spring-boot:run`.
2. Open Postman or VS Code REST Client.
3. Send requests to the endpoints listed above.
4. Verify JSON responses and status codes:
   - `201 Created` for create
   - `200 OK` for fetch, update, delete
   - `404 Not Found` for missing order

## Automated Test

Run:

```bash
mvn test
```
