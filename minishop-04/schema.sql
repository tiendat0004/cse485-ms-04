CREATE DATABASE IF NOT EXISTS minishop_cse485
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE minishop_cse485;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name, description) VALUES
('Ban phim', 'Danh muc ban phim co / membrane'),
('Chuot', 'Danh muc chuot may tinh'),
('Man hinh', 'Danh muc man hinh');