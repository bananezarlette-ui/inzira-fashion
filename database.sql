-- Inzira Fashion Database
-- Run this file to set up the database schema and seed data

CREATE DATABASE IF NOT EXISTS inzira_fashion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inzira_fashion;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('customer','admin') DEFAULT 'customer',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL UNIQUE,
    slug  VARCHAR(100) NOT NULL UNIQUE
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200) NOT NULL,
    description TEXT,
    price       DECIMAL(12,2) NOT NULL,
    stock       INT DEFAULT 0,
    image_url   VARCHAR(500),
    category_id INT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NULL,
    customer_name    VARCHAR(100) NOT NULL,
    customer_email   VARCHAR(150) NOT NULL,
    customer_phone   VARCHAR(20)  NOT NULL,
    customer_address TEXT NOT NULL,
    total            DECIMAL(12,2) NOT NULL,
    status           ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT NOT NULL,
    product_id  INT NOT NULL,
    quantity    INT NOT NULL,
    price       DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ── Seed Data ──
INSERT IGNORE INTO categories (name, slug) VALUES
('Women',       'women'),
('Men',         'men'),
('Kids',        'kids'),
('Accessories', 'accessories'),
('Shoes',       'shoes');

INSERT IGNORE INTO products (name, description, price, stock, image_url, category_id) VALUES
('Floral Summer Dress',   'Light and elegant floral dress perfect for warm Rwandan weather.',           45000, 20, 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=600', 1),
('Kente Print Blouse',    'Beautiful blouse with traditional African Kente print.',                    28000, 15, 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=600', 1),
('Ankara Wrap Skirt',     'Vibrant Ankara fabric wrap skirt, handcrafted locally.',                   32000, 18, 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=600', 1),
('Men''s Linen Shirt',    'Classic linen shirt — breathable and stylish for any occasion.',            35000, 25, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600', 2),
('Slim Fit Chinos',       'Modern slim fit chino trousers in premium cotton blend.',                   42000, 30, 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600', 2),
('Denim Jacket',          'Classic denim jacket with a contemporary African embroidery touch.',        55000, 12, 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=600', 2),
('Leather Handbag',       'Genuine leather handbag with multiple compartments.',                       65000, 10, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600', 4),
('Beaded Necklace',       'Handmade Rwandan beaded necklace, fair trade certified.',                   12000, 40, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600', 4),
('Woven Basket Bag',      'Eco-friendly woven basket bag made by local artisans.',                    18000, 22, 'https://images.unsplash.com/photo-1591561954557-26941169b49e?w=600', 4),
('Kids Ankara Set',       'Colorful Ankara top and shorts set for children aged 3–8.',                22000, 35, 'https://images.unsplash.com/photo-1622290291468-a28f7a7dc6a8?w=600', 3);

-- Admin user (password: Admin@1234)
INSERT IGNORE INTO users (name, email, password, role) VALUES
('Admin', 'admin@inzirafashion.rw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
