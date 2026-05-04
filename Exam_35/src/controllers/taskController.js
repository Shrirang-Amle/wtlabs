const taskModel = require('../models/taskModel');

// GET /api/tasks?status=pending|completed
const getAllTasks = (req, res) => {
  const { status } = req.query;

  const validStatuses = ['pending', 'completed'];
  if (status && !validStatuses.includes(status)) {
    return res.status(400).json({
      success: false,
      message: `Invalid status filter. Use 'pending' or 'completed'.`,
    });
  }

  const tasks = taskModel.getAllTasks(status);
  res.json({
    success: true,
    count: tasks.length,
    data: tasks,
  });
};

// GET /api/tasks/:id
const getTaskById = (req, res) => {
  const task = taskModel.getTaskById(req.params.id);
  if (!task) {
    return res.status(404).json({ success: false, message: 'Task not found' });
  }
  res.json({ success: true, data: task });
};

// POST /api/tasks
const createTask = (req, res) => {
  const { title, description, priority } = req.body;

  if (!title || typeof title !== 'string' || title.trim() === '') {
    return res.status(400).json({
      success: false,
      message: 'Title is required and must be a non-empty string.',
    });
  }

  const validPriorities = ['low', 'medium', 'high'];
  if (priority && !validPriorities.includes(priority)) {
    return res.status(400).json({
      success: false,
      message: `Invalid priority. Use 'low', 'medium', or 'high'.`,
    });
  }

  const task = taskModel.createTask({
    title: title.trim(),
    description,
    priority,
  });

  res.status(201).json({ success: true, data: task });
};

// PUT /api/tasks/:id
const updateTask = (req, res) => {
  const { title, description, priority } = req.body;

  if (title !== undefined && (typeof title !== 'string' || title.trim() === '')) {
    return res.status(400).json({
      success: false,
      message: 'Title must be a non-empty string.',
    });
  }

  const validPriorities = ['low', 'medium', 'high'];
  if (priority && !validPriorities.includes(priority)) {
    return res.status(400).json({
      success: false,
      message: `Invalid priority. Use 'low', 'medium', or 'high'.`,
    });
  }

  const task = taskModel.updateTask(req.params.id, {
    title: title ? title.trim() : undefined,
    description,
    priority,
  });

  if (!task) {
    return res.status(404).json({ success: false, message: 'Task not found' });
  }

  res.json({ success: true, data: task });
};

// PATCH /api/tasks/:id/status
const updateTaskStatus = (req, res) => {
  const { status } = req.body;

  const validStatuses = ['pending', 'completed'];
  if (!status || !validStatuses.includes(status)) {
    return res.status(400).json({
      success: false,
      message: `Status is required. Use 'pending' or 'completed'.`,
    });
  }

  const task = taskModel.updateTaskStatus(req.params.id, status);
  if (!task) {
    return res.status(404).json({ success: false, message: 'Task not found' });
  }

  res.json({ success: true, data: task });
};

// DELETE /api/tasks/:id
const deleteTask = (req, res) => {
  const task = taskModel.deleteTask(req.params.id);
  if (!task) {
    return res.status(404).json({ success: false, message: 'Task not found' });
  }

  res.json({
    success: true,
    message: 'Task deleted successfully',
    data: task,
  });
};

module.exports = {
  getAllTasks,
  getTaskById,
  createTask,
  updateTask,
  updateTaskStatus,
  deleteTask,
};
