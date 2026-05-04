const { v4: uuidv4 } = require('uuid');

// In-memory store — swap this out for a database later
let tasks = [];

/**
 * Returns all tasks, with optional filtering by status.
 * @param {string|undefined} status - 'pending' | 'completed' | undefined
 */
function getAllTasks(status) {
  if (status) {
    return tasks.filter((t) => t.status === status);
  }
  return [...tasks];
}

/**
 * Returns a single task by ID, or null if not found.
 */
function getTaskById(id) {
  return tasks.find((t) => t.id === id) || null;
}

/**
 * Creates a new task and returns it.
 * @param {object} data - { title, description?, priority? }
 */
function createTask({ title, description = '', priority = 'medium' }) {
  const task = {
    id: uuidv4(),
    title,
    description,
    priority,          // 'low' | 'medium' | 'high'
    status: 'pending', // 'pending' | 'completed'
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
  };
  tasks.push(task);
  return task;
}

/**
 * Updates allowed fields on a task and returns the updated task.
 * Returns null if the task is not found.
 */
function updateTask(id, { title, description, priority }) {
  const index = tasks.findIndex((t) => t.id === id);
  if (index === -1) return null;

  if (title !== undefined)       tasks[index].title       = title;
  if (description !== undefined) tasks[index].description = description;
  if (priority !== undefined)    tasks[index].priority    = priority;
  tasks[index].updatedAt = new Date().toISOString();

  return tasks[index];
}

/**
 * Updates only the status of a task.
 * Returns null if the task is not found.
 */
function updateTaskStatus(id, status) {
  const index = tasks.findIndex((t) => t.id === id);
  if (index === -1) return null;

  tasks[index].status    = status;
  tasks[index].updatedAt = new Date().toISOString();

  return tasks[index];
}

/**
 * Deletes a task by ID.
 * Returns the deleted task, or null if not found.
 */
function deleteTask(id) {
  const index = tasks.findIndex((t) => t.id === id);
  if (index === -1) return null;

  const [deleted] = tasks.splice(index, 1);
  return deleted;
}

module.exports = {
  getAllTasks,
  getTaskById,
  createTask,
  updateTask,
  updateTaskStatus,
  deleteTask,
};
