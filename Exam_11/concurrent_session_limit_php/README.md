# Simple PHP Concurrent Session Limit

This version is kept simple for a lab exam.

It supports:

- Maximum 3 concurrent sessions per user
- Session timeout after 5 minutes
- No database, only one JSON file

## Important Files

- `index.php` - Main code
- `logout.php` - Logout redirect
- `data/session_data.json` - Stores active sessions

## Run the Program

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000/index.php
```

## Test

1. Open 3 browsers or incognito windows
2. Login with the same username
3. On 4th login, access will be denied
4. After 5 minutes of inactivity, the session expires

## Why this is exam-friendly

- Easy to write
- Easy to explain
- Fewer files
- Covers the full problem statement
