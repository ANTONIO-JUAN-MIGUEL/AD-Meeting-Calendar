CREATE TABLE IF NOT EXISTS tasks (
    id SERIAL PRIMARY KEY,
    meeting_id INTEGER REFERENCES meetings (id),
    assigned_to INTEGER REFERENCES users (id),
    title VARCHAR(100),
    description TEXT,
    status VARCHAR(50) DEFAULT 'pending',
    due_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);