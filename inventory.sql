CREATE DATABASE IF NOT EXISTS inventory_system;
USE inventory_system;

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    quantity INT DEFAULT 0,
    unit_price DECIMAL(10,2) DEFAULT 0.00,
    min_quantity INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT,
    date DATE,
    total_amount DECIMAL(10,2),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);

CREATE TABLE purchase_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT,
    item_id INT,
    quantity INT,
    unit_price DECIMAL(10,2),
    FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

CREATE TABLE stock_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    quantity INT,
    issued_to VARCHAR(255),
    issue_type ENUM('doctor', 'session') DEFAULT 'doctor',
    date DATE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE SET NULL
);

-- sample data
INSERT INTO items (name, description, quantity, unit_price, min_quantity) VALUES
('Syringe 5ml', 'Disposable syringe', 120, 0.50, 20),
('Gauze Pack', 'Sterile gauze', 50, 1.20, 10),
('Surgical Mask', 'Disposable mask', 300, 0.15, 50);

INSERT INTO suppliers (name, contact_info) VALUES
('MedSupply Co', 'medsupply@example.com'),
('HealthPro', 'contact@healthpro.com');
