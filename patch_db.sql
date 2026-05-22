USE blossom_db;

DROP TABLE IF EXISTS items;
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    customization_details TEXT NOT NULL
);

ALTER TABLE orders
ADD COLUMN item_id INT NULL AFTER order_id,
ADD COLUMN item_type VARCHAR(50) NULL AFTER item_id;
