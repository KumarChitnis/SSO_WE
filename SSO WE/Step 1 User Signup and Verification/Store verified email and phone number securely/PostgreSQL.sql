CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    email_salt VARCHAR(255) NOT NULL,
    email_hash VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    phone_number_salt VARCHAR(255) NOT NULL,
    phone_number_hash VARCHAR(255) NOT NULL
);