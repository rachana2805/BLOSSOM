USE blossom_db;
INSERT INTO items (name, description, price, customization_details) 
SELECT name, category, price, '{}' FROM products;

TRUNCATE TABLE products;
