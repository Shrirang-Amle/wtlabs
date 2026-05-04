# Online Book Store

Simple responsive online book store using Spring Boot, Thymeleaf, and MySQL.

## Pages

1. Home Page
2. Login Page
3. Catalog Page
4. Registration Page

## Technologies

- Spring Boot
- Thymeleaf
- MySQL
- HTML
- CSS

## Database

Default MySQL settings in `src/main/resources/application.properties`:

- Database: `online_book_store`
- Username: `root`
- Password: `root`

You can create the database manually with `database.sql`, or let Spring Boot create it automatically.

## Run

```bash
mvn spring-boot:run
```

Open:

`http://localhost:8080`

## Project Structure

- `src/main/java/com/bookstore/controller` - Controllers
- `src/main/java/com/bookstore/model` - Entity classes
- `src/main/java/com/bookstore/repository` - Repository interfaces
- `src/main/resources/templates` - HTML pages
- `src/main/resources/static/css` - CSS file
