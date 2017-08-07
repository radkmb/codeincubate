SELECT id, name, price
FROM products
WHERE name 'マウスパッド';

SELECT id, name, price
FROM products
WHERE name LIKE '%パッド%';

SELECT id, name, price
FROM products
WHERE name NOT LIKE '%パッド%' AND price >= 500;