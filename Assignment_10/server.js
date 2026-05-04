const express = require("express");
const path = require("path");
const { pool, initializeDatabase, tableName } = require("./db");

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, "public")));

app.post("/api/students", async (req, res) => {
  const { name, email, course } = req.body;

  if (!name || !email || !course) {
    return res.status(400).json({
      message: "Name, email, and course are required.",
    });
  }

  try {
    const [result] = await pool.execute(
      `INSERT INTO \`${tableName}\` (name, email, course) VALUES (?, ?, ?)`,
      [name.trim(), email.trim(), course.trim()]
    );

    return res.status(201).json({
      message: "Student registered successfully.",
      student: {
        id: result.insertId,
        name: name.trim(),
        email: email.trim(),
        course: course.trim(),
      },
    });
  } catch (error) {
    if (error.code === "ER_DUP_ENTRY") {
      return res.status(409).json({
        message: "A student with this email is already registered.",
      });
    }

    console.error("Failed to insert student:", error);
    return res.status(500).json({
      message: "Unable to register the student right now.",
    });
  }
});

app.get("/api/students", async (_req, res) => {
  try {
    const [rows] = await pool.execute(
      `SELECT id, name, email, course FROM \`${tableName}\` ORDER BY id DESC`
    );
    return res.json(rows);
  } catch (error) {
    console.error("Failed to fetch students:", error);
    return res.status(500).json({
      message: "Unable to fetch student records right now.",
    });
  }
});

app.use((err, _req, res, _next) => {
  console.error("Unhandled error:", err);
  res.status(500).json({ message: "Something went wrong." });
});

async function startServer() {
  try {
    await initializeDatabase();
    app.listen(PORT, () => {
      console.log(`Server running at http://localhost:${PORT}`);
    });
  } catch (error) {
    console.error("Failed to start the application:", error.message);
    process.exit(1);
  }
}

startServer();
