-- CICA-GPRO — PostgreSQL initialization
-- This runs automatically on first container start

-- Ensure extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- Grant privileges (user already created by POSTGRES_USER env)
GRANT ALL PRIVILEGES ON DATABASE cica_gpro TO gpro;
