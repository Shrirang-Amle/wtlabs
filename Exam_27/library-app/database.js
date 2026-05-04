const Database = require('better-sqlite3');

// Open (or create) the SQLite database file
const db = new Database('library.db');

// 1. Create the Book table if it doesn't exist
db.exec(`
  CREATE TABLE IF NOT EXISTS books (
    book_id   INTEGER PRIMARY KEY AUTOINCREMENT,
    title     TEXT    NOT NULL,
    author    TEXT    NOT NULL,
    year      INTEGER NOT NULL
  )
`);

// 2. Seed some initial book records (only if the table is empty)
const count = db.prepare('SELECT COUNT(*) AS cnt FROM books').get().cnt;
if (count === 0) {
  const insert = db.prepare(
    'INSERT INTO books (title, author, year) VALUES (?, ?, ?)'
  );

  const seedBooks = [
    ['The Great Gatsby',          'F. Scott Fitzgerald', 1925],
    ['To Kill a Mockingbird',     'Harper Lee',          1960],
    ['1984',                      'George Orwell',       1949],
    ['Pride and Prejudice',       'Jane Austen',         1813],
    ['The Catcher in the Rye',    'J.D. Salinger',       1951],
  ];

  const insertMany = db.transaction((books) => {
    for (const [title, author, year] of books) {
      insert.run(title, author, year);
    }
  });

  insertMany(seedBooks);
  console.log('Database seeded with initial book records.');
}

module.exports = db;
