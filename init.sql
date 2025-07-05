-- Initialize Robot Club TO database
CREATE TABLE IF NOT EXISTS applications (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    pronouns VARCHAR(100),
    phone VARCHAR(20),
    social_media TEXT,
    age_confirmed BOOLEAN NOT NULL DEFAULT FALSE,
    liability_waiver_agreed BOOLEAN NOT NULL DEFAULT FALSE,
    about_yourself TEXT NOT NULL,
    project_interests TEXT NOT NULL,
    member_references TEXT NOT NULL,
    tier_interest VARCHAR(50),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create index on email for faster lookups
CREATE INDEX IF NOT EXISTS idx_applications_email ON applications(email);

-- Create index on submission date
CREATE INDEX IF NOT EXISTS idx_applications_submitted_at ON applications(submitted_at);