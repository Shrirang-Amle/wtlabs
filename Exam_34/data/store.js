/**
 * In-memory data store for blog posts.
 * Acts as a simple database substitute — data resets on server restart.
 *
 * Each post has the shape:
 * {
 *   id:        string   — unique UUID
 *   title:     string   — post title
 *   content:   string   — post body
 *   author:    string   — author name
 *   tags:      string[] — optional list of tags
 *   createdAt: string   — ISO timestamp
 *   updatedAt: string   — ISO timestamp
 * }
 */

const posts = [
  {
    id: '1',
    title: 'Getting Started with Express.js',
    content: 'Express.js is a minimal and flexible Node.js web application framework...',
    author: 'Alice',
    tags: ['nodejs', 'express', 'backend'],
    createdAt: new Date('2026-01-10').toISOString(),
    updatedAt: new Date('2026-01-10').toISOString(),
  },
  {
    id: '2',
    title: 'REST API Design Best Practices',
    content: 'Designing a clean REST API requires careful thought about resources and HTTP verbs...',
    author: 'Bob',
    tags: ['api', 'rest', 'design'],
    createdAt: new Date('2026-02-15').toISOString(),
    updatedAt: new Date('2026-02-15').toISOString(),
  },
];

module.exports = posts;
