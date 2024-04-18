CREATE DATABASE IF NOT EXISTS user_management_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    label VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS user_role (
    user_id INT,
    role_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

INSERT INTO users (first_name, last_name, email, password)
VALUES ('Admin', 'User', 'admin@email.com', 'YWRtaW4='); -- Base64 decoded password of 'admin'

INSERT INTO roles (name, label) VALUES ('admin', 'Admin');
INSERT INTO roles (name, label) VALUES ('user', 'User');

INSERT INTO user_role (user_id, role_id) VALUES (1, 1);