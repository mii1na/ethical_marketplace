USE ethical_marketplace;

INSERT INTO vendors (business_name, owner_name, email, password_hash, category, location, sustainability_rating, description)
VALUES (
    'Green Roots Market',
    'Mina Fahim',
    'vendor@example.com',
    '$2y$12$zdZIlHLVg3aOFbizmYCBhOGJ8.Qq1EqDge7eZMTwMloexZTar0eTy',
    'Home Goods',
    'Toronto',
    4.8,
    'Ethical products from local small businesses.'
);

INSERT INTO products (vendor_id, name, category, price, stock, sustainability_rating, description)
VALUES
(1, 'Reusable Bamboo Cutlery Set', 'Kitchen', 18.99, 50, 4.7, 'Portable eco-friendly cutlery set.'),
(1, 'Organic Cotton Tote Bag', 'Accessories', 15.50, 80, 4.9, 'Reusable tote bag made from organic cotton.'),
(1, 'Natural Soy Candle', 'Home Decor', 22.00, 35, 4.6, 'Hand-poured candle made with soy wax.');

INSERT INTO orders (product_id, customer_name, customer_email, quantity, total_amount, status)
VALUES
(1, 'Sara Khan', 'sara@example.com', 2, 37.98, 'pending'),
(2, 'John Lee', 'john@example.com', 1, 15.50, 'shipped');
