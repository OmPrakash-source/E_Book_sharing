-- Create the database
CREATE DATABASE IF NOT EXISTS ebook_platform;
USE ebook_platform;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

-- Create files table
CREATE TABLE IF NOT EXISTS files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    filename VARCHAR(255),
    downloads INT DEFAULT 0,
    likes INT DEFAULT 0,
    upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create likes table
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    file_id INT,
    UNIQUE KEY unique_like (user_id, file_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE
);

-- Insert optional admin user (email: omjhade12@gmail.com, pass: 123)
-- Password: '123' hashed with bcrypt
INSERT INTO users (name, email, password, role) VALUES
('Admin Om', 'omjhade12@gmail.com', '$2y$10$5Qzzk/3EfdLPpyDbKy4TduDzAJFysGqUVKHpY1p0AVOqLHOtu79J2', 'admin');
