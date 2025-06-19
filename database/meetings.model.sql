CREATE TABLE IF NOT EXISTS meetings (
    id SERIAL PRIMARY KEY,
    project_id INTEGER REFERENCES projects (id),
    title VARCHAR(100) NOT NULL,
    agenda TEXT,
    scheduled_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);