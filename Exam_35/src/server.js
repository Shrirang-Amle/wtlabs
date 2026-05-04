const express = require('express');
const taskRoutes = require('./routes/taskRoutes');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.json());

// Routes
app.use('/api/tasks', taskRoutes);

// Root route
app.get('/', (req, res) => {
  res.json({
    message: 'Task Manager API',
    version: '1.0.0',
    endpoints: {
      'GET    /api/tasks':          'Retrieve all tasks',
      'GET    /api/tasks/:id':      'Retrieve a single task',
      'POST   /api/tasks':          'Create a new task',
      'PUT    /api/tasks/:id':      'Update a task',
      'PATCH  /api/tasks/:id/status': 'Update task status',
      'DELETE /api/tasks/:id':      'Delete a task',
    },
  });
});

// 404 handler
app.use((req, res) => {
  res.status(404).json({ success: false, message: 'Route not found' });
});

// Global error handler
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ success: false, message: 'Internal server error' });
});

app.listen(PORT, () => {
  console.log(`Task Manager API running on http://localhost:${PORT}`);
});

module.exports = app;
