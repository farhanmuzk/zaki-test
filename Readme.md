# Jawaban Query Database MySQL

## 1. Query 1: Total Item dan Harga Pesanan

Menghitung jumlah total item dan total harga dari setiap pesanan.


```sql
sql
Salin kode
SELECT
    orders.id AS order_id,
    orders.customer_name,
    orders.order_date,
    COUNT(order_items.id) AS total_items,
    SUM(order_items.quantity * order_items.price) AS total_price
FROM
    orders
JOIN
    order_items ON orders.id = order_items.order_id
GROUP BY
    orders.id, orders.customer_name, orders.order_date;

```


## 2. Query 2: Pesanan dengan Harga Total Tertinggi


Mencari pesanan dengan total harga tertinggi.


```sql
sql
Salin kode
SELECT
    orders.id AS order_id,
    orders.customer_name,
    orders.order_date,
    SUM(order_items.quantity * order_items.price) AS total_price
FROM
    orders
JOIN
    order_items ON orders.id = order_items.order_id
GROUP BY
    orders.id, orders.customer_name, orders.order_date
ORDER BY
    total_price DESC
LIMIT 1;

```


## 3. Query 3: Produk Terpopuler


Menampilkan produk yang paling banyak dipesan berdasarkan jumlah total quantity di semua pesanan.

### Query

```sql
sql
Salin kode
SELECT
    products.id AS product_id,
    products.name AS product_name,
    SUM(order_items.quantity) AS total_quantity
FROM
    products
JOIN
    order_items ON products.id = order_items.product_id
GROUP BY
    products.id, products.name
ORDER BY
    total_quantity DESC
LIMIT 1;

```

## 4. Query 4: Stok Produk Setelah Pemesanan


Menampilkan stok awal dan stok yang tersisa setelah semua pesanan dilakukan.


```sql
sql
Salin kode
SELECT
    products.id AS product_id,
    products.name AS product_name,
    products.stock AS initial_stock,
    products.stock - COALESCE(SUM(order_items.quantity), 0) AS remaining_stock
FROM
    products
LEFT JOIN
    order_items ON products.id = order_items.product_id
GROUP BY
    products.id, products.name, products.stock;

```

## 5. Query 5: Pesanan pada Bulan Tertentu

Menampilkan semua pesanan yang dibuat pada bulan tertentu (misalnya, November 2024).


```sql
sql
Salin kode
SELECT
    orders.id AS order_id,
    orders.customer_name,
    orders.order_date,
    SUM(order_items.quantity * order_items.price) AS total_price
FROM
    orders
JOIN
    order_items ON orders.id = order_items.order_id
WHERE
    EXTRACT(MONTH FROM orders.order_date) = 11
    AND EXTRACT(YEAR FROM orders.order_date) = 2024
GROUP BY
    orders.id, orders.customer_name, orders.order_date;

```

