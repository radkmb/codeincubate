SELECT id, name, price
FROM products
WHERE price BETWEEN 500 AND 1000;

SELECT id, name, price
FROM products
WHERE id >= 3 AND price >= 500;

SELECT id, name, price
FROM products
WHERE price <= 1500 AND name != '傘';