const express = require('express');
const router = express.Router();
const {
  getAllPosts,
  getPostById,
  createPost,
  updatePost,
  deletePost,
} = require('../controllers/blogController');

// GET    /api/posts          — list all posts (supports ?author= and ?tag= filters)
router.get('/', getAllPosts);

// GET    /api/posts/:id      — get a single post
router.get('/:id', getPostById);

// POST   /api/posts          — create a new post
router.post('/', createPost);

// PUT    /api/posts/:id      — update an existing post
router.put('/:id', updatePost);

// DELETE /api/posts/:id      — delete a post
router.delete('/:id', deletePost);

module.exports = router;
