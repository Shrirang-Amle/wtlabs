const { v4: uuidv4 } = require('uuid');
const posts = require('../data/store');

// ─── GET /api/posts ───────────────────────────────────────────────────────────
// Returns all posts, with optional ?author= and ?tag= query filters.
const getAllPosts = (req, res) => {
  let result = [...posts];

  // Filter by author (case-insensitive)
  if (req.query.author) {
    result = result.filter(
      (p) => p.author.toLowerCase() === req.query.author.toLowerCase()
    );
  }

  // Filter by tag
  if (req.query.tag) {
    result = result.filter((p) =>
      p.tags.map((t) => t.toLowerCase()).includes(req.query.tag.toLowerCase())
    );
  }

  res.status(200).json({
    success: true,
    count: result.length,
    data: result,
  });
};

// ─── GET /api/posts/:id ───────────────────────────────────────────────────────
const getPostById = (req, res) => {
  const post = posts.find((p) => p.id === req.params.id);

  if (!post) {
    return res.status(404).json({ success: false, message: `Post with id '${req.params.id}' not found` });
  }

  res.status(200).json({ success: true, data: post });
};

// ─── POST /api/posts ──────────────────────────────────────────────────────────
const createPost = (req, res) => {
  const { title, content, author, tags } = req.body;

  // Validate required fields
  if (!title || !content || !author) {
    return res.status(400).json({
      success: false,
      message: 'title, content, and author are required fields',
    });
  }

  const newPost = {
    id: uuidv4(),
    title: title.trim(),
    content: content.trim(),
    author: author.trim(),
    tags: Array.isArray(tags) ? tags : [],
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString(),
  };

  posts.push(newPost);

  res.status(201).json({ success: true, message: 'Post created successfully', data: newPost });
};

// ─── PUT /api/posts/:id ───────────────────────────────────────────────────────
const updatePost = (req, res) => {
  const index = posts.findIndex((p) => p.id === req.params.id);

  if (index === -1) {
    return res.status(404).json({ success: false, message: `Post with id '${req.params.id}' not found` });
  }

  const { title, content, author, tags } = req.body;

  // At least one field must be provided
  if (!title && !content && !author && !tags) {
    return res.status(400).json({
      success: false,
      message: 'Provide at least one field to update: title, content, author, or tags',
    });
  }

  // Merge existing post with updated fields
  const updatedPost = {
    ...posts[index],
    ...(title   && { title:   title.trim() }),
    ...(content && { content: content.trim() }),
    ...(author  && { author:  author.trim() }),
    ...(Array.isArray(tags) && { tags }),
    updatedAt: new Date().toISOString(),
  };

  posts[index] = updatedPost;

  res.status(200).json({ success: true, message: 'Post updated successfully', data: updatedPost });
};

// ─── DELETE /api/posts/:id ────────────────────────────────────────────────────
const deletePost = (req, res) => {
  const index = posts.findIndex((p) => p.id === req.params.id);

  if (index === -1) {
    return res.status(404).json({ success: false, message: `Post with id '${req.params.id}' not found` });
  }

  const deleted = posts.splice(index, 1)[0];

  res.status(200).json({ success: true, message: 'Post deleted successfully', data: deleted });
};

module.exports = { getAllPosts, getPostById, createPost, updatePost, deletePost };
