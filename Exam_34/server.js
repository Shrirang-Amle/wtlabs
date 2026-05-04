const express = require('express');
const blogRoutes = require('./routes/blogRoutes');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware to parse JSON request bodies
app.use(express.json());

// Root route
app.get('/', (req, res) => {
  res.json({
    message: 'Welcome to the Blog Management API',
    version: '1.0.0',
    endpoints: {
      'GET    /api/posts':          'Get all blog posts',
      'GET    /api/posts/:id':      'Get a single blog post by ID',
      'POST   /api/posts':          'Create a new blog post',
      'PUT    /api/posts/:id':      'Update an existing blog post',
      'DELETE /api/posts/:id':      'Delete a blog post',
    },
  });
});

// Blog routes
app.use('/api/posts', blogRoutes);

// 404 handler for unknown routes
app.use((req, res) => {
  res.status(404).json({ success: false, message: 'Route not found' });
});

// Global error handler
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ success: false, message: 'Internal server error' });
});

app.listen(PORT, () => {
  console.log(`Blog API server running at http://localhost:${PORT}`);
});

module.exports = app;
