const mysql = require("mysql2/promise");
require("dotenv").config();

const requiredConfig = [
  "DB_HOST",
  "DB_USER",
  "DB_PASSWORD",
  "DB_NAME",
];

for (const key of requiredConfig) {
  if (process.env[key] === undefined) {
    throw new Error(`Missing required environment variable: ${key}`);
  }
}

const pool = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

const tableName = process.env.DB_TABLE || "students";

if (!/^[A-Za-z_][A-Za-z0-9_]*$/.test(tableName)) {
  throw new Error("DB_TABLE must contain only letters, numbers, and underscores.");
}

async function initializeDatabase() {
  await pool.execute(`
    CREATE TABLE IF NOT EXISTS \`${tableName}\` (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      email VARCHAR(100) NOT NULL UNIQUE,
      course VARCHAR(100) NOT NULL
    )
  `);

  const [columns] = await pool.query(`SHOW COLUMNS FROM \`${tableName}\``);
  const idColumn = columns.find((column) => column.Field === "id");

  if (!idColumn) {
    throw new Error(`The table "${tableName}" must contain an "id" column.`);
  }

  if (!String(idColumn.Extra || "").toLowerCase().includes("auto_increment")) {
    await pool.execute(`
      ALTER TABLE \`${tableName}\`
      MODIFY id INT NOT NULL AUTO_INCREMENT
    `);
  }
}

module.exports = {
  pool,
  initializeDatabase,
  tableName,
};
