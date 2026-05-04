const express = require('express');
const path    = require('path');
const db      = require('./database');

const app  = express();
const PORT = 3000;

// Middleware
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// ─── Routes ────────────────────────────────────────────────────────────────

// 3. GET /api/books  – retrieve all books
app.get('/api/books', (req, res) => {
  try {
    const books = db.prepare('SELECT * FROM books ORDER BY book_id').all();
    res.json({ success: true, data: books });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// GET /api/books/:id – retrieve a single book
app.get('/api/books/:id', (req, res) => {
  try {
    const book = db.prepare('SELECT * FROM books WHERE book_id = ?').get(req.params.id);
    if (!book) return res.status(404).json({ success: false, message: 'Book not found' });
    res.json({ success: true, data: book });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// POST /api/books  – add a new book
app.post('/api/books', (req, res) => {
  const { title, author, year } = req.body;

  // Basic validation
  if (!title || !author || !year) {
    return res.status(400).json({ success: false, message: 'title, author, and year are required.' });
  }
  if (!Number.isInteger(Number(year)) || year < 1) {
    return res.status(400).json({ success: false, message: 'year must be a positive integer.' });
  }

  try {
    const stmt   = db.prepare('INSERT INTO books (title, author, year) VALUES (?, ?, ?)');
    const result = stmt.run(title.trim(), author.trim(), Number(year));
    const newBook = db.prepare('SELECT * FROM books WHERE book_id = ?').get(result.lastInsertRowid);
    res.status(201).json({ success: true, data: newBook });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// DELETE /api/books/:id – remove a book
app.delete('/api/books/:id', (req, res) => {
  try {
    const result = db.prepare('DELETE FROM books WHERE book_id = ?').run(req.params.id);
    if (result.changes === 0) {
      return res.status(404).json({ success: false, message: 'Book not found' });
    }
    res.json({ success: true, message: 'Book deleted successfully.' });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// ─── Start server ───────────────────────────────────────────────────────────
app.listen(PORT, () => {
  console.log(`Library app running at http://localhost:${PORT}`);
});
