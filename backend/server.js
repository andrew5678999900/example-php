
require('dotenv').config();
const express = require('express');
const cors = require('cors');
const { Pool } = require('pg');

const app = express();
const port = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());

// PostgreSQL connection pool
const pool = new Pool({
  host: process.env.DATABASE_HOST,
  user: process.env.DATABASE_USER,
  password: process.env.DATABASE_PASSWORD,
  database: process.env.DATABASE_NAME,
  port: process.env.DATABASE_PORT || 5432,
  ssl: {
    rejectUnauthorized: false,
  },
});

// Test database connection
pool.connect((err) => {
  if (err) {
    console.error('Database connection error:', err.stack);
  } else {
    console.log('Connected to the database.');
  }
});

// Routes

// Get all spells
app.get('/spells', async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM spells ORDER BY id ASC');
    res.status(200).json(result.rows);
  } catch (err) {
    console.error('Error fetching spells:', err);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Add a new spell
app.post('/spells', async (req, res) => {
  const { name, level, type, effect, description, components } = req.body;
  if (!name || !level || !type || !effect || !description || !components) {
    return res.status(400).json({ error: 'All fields are required' });
  }

  try {
    const result = await pool.query(
      'INSERT INTO spells (name, level, type, effect, description, components) VALUES ($1, $2, $3, $4, $5, $6) RETURNING *',
      [name, level, type, effect, description, components]
    );
    res.status(201).json(result.rows[0]);
  } catch (err) {
    console.error('Error adding spell:', err);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Start the server
app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});

