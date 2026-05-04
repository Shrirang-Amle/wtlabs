const express = require('express');
const router = express.Router();
const {
  getAllTasks,
  getTaskById,
  createTask,
  updateTask,
  updateTaskStatus,
  deleteTask,
} = require('../controllers/taskController');

// GET    /api/tasks          — list all tasks (optional ?status= filter)
// POST   /api/tasks          — create a new task
router.route('/').get(getAllTasks).post(createTask);

// PATCH  /api/tasks/:id/status — update only the status
router.patch('/:id/status', updateTaskStatus);

// GET    /api/tasks/:id      — get one task
// PUT    /api/tasks/:id      — update title / description / priority
// DELETE /api/tasks/:id      — delete a task
router.route('/:id').get(getTaskById).put(updateTask).delete(deleteTask);

module.exports = router;
