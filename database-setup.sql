-- Neon.tech Database Setup for Light & Style
-- Run this SQL in your Neon.tech SQL Editor

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    material VARCHAR(255),
    wattage INTEGER,
    size VARCHAR(100),
    finish VARCHAR(100),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Clear existing data (if any)
DELETE FROM products;

-- Insert sample lighting products
INSERT INTO products (name, category, material, wattage, size, finish, image_url) VALUES
('Elegant Gold Chandelier', 'Chandelier', 'Crystal + Brass', 60, '24 inches', 'Antique Gold', 'images/chandelier1.jpeg'),
('Modern Ring Chandelier', 'Chandelier', 'Aluminum + Acrylic', 45, '20 inches', 'Chrome', 'images/chandelier2.jpeg'),
('Glass Pendant Light', 'Pendant', 'Glass + Steel', 25, '8 inches', 'Black', 'images/chandelier1.jpeg'),
('LED Track Light', 'Track Light', 'Metal', 30, '12 inches', 'White', 'images/chandelier2.jpeg'),
('Modern LED Strip', 'LED Strip', 'Aluminum', 12, '1 meter', 'White', 'images/chandelier1.jpeg'),
('Vintage Brass Pendant', 'Pendant', 'Brass + Glass', 40, '10 inches', 'Antique Brass', 'images/chandelier2.jpeg'),
('Crystal Chandelier Deluxe', 'Chandelier', 'Crystal + Gold', 80, '36 inches', 'Gold', 'images/chandelier1.jpeg'),
('Minimalist Track System', 'Track Light', 'Aluminum', 35, '48 inches', 'Matte Black', 'images/chandelier2.jpeg'),
('Industrial Hanging Light', 'Pendant', 'Metal + Edison Bulb', 60, '14 inches', 'Rust', 'images/chandelier1.jpeg'),
('Smart LED Panel', 'LED Panel', 'Plastic + LED', 20, '12x12 inches', 'White', 'images/chandelier2.jpeg'),
('Art Deco Table Lamp', 'Table Lamp', 'Brass + Fabric', 25, '18 inches', 'Bronze', 'images/chandelier1.jpeg'),
('Modern Ceiling Fan Light', 'Ceiling Fan', 'Metal + Wood', 75, '52 inches', 'Brushed Nickel', 'images/chandelier2.jpeg');

-- Create categories table for future use
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert categories
INSERT INTO categories (name, description) VALUES
('Chandelier', 'Elegant hanging light fixtures for dining rooms and entryways'),
('Pendant', 'Hanging lights perfect for kitchen islands and task lighting'),
('Track Light', 'Adjustable spotlight systems for accent and general lighting'),
('LED Strip', 'Flexible LED lighting for under-cabinet and accent applications'),
('Table Lamp', 'Portable lighting solutions for desks and side tables'),
('Ceiling Fan', 'Combination lighting and ventilation fixtures'),
('LED Panel', 'Modern flat panel lights for offices and contemporary spaces');

-- Verify the data
SELECT COUNT(*) as total_products FROM products;
SELECT COUNT(*) as total_categories FROM categories;
