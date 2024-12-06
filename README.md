# Soal Teori

## 1. Laravel Lumen

### 1.1 Perbedaan Laravel dan Laravel Lumen

Laravel dan Laravel Lumen memiliki tujuan penggunaan yang berbeda. Berikut adalah perbedaan utama:

- **Laravel**: Framework lengkap yang cocok untuk aplikasi web berskala besar dengan kebutuhan fitur kompleks seperti session management, templating, dan lainnya.
- **Laravel Lumen**: Versi ringan dari Laravel yang dirancang untuk aplikasi mikroservice dan API dengan performa tinggi.

### 1.2 Konsep Middleware di Laravel Lumen

Middleware di Laravel Lumen adalah mekanisme untuk memfilter permintaan yang masuk ke aplikasi. seperti memverifikasi autentikasi pengguna sebelum mengakses aplikasi dan semua middleware harus disimpan dalam direktori ```app/Http/Middleware```.

**Contoh Penggunaan:**

```php
// Definisi middleware
$app->routeMiddleware([
    'auth' => App\\Http\\Middleware\\Authenticate::class,
]);

// Penerapan middleware di route
$app->get('/user', ['middleware' => 'auth', function () {
    return 'Authenticated User';
}]);

```

### 1.3 Dependency Injection di Laravel Lumen

Dependency Injection adalah pola desain yang memungkinkan pengelolaan dependency melalui constructor atau metode. Lumen memanfaatkan ini untuk meningkatkan modularitas dan testabilitas kode.

**Contoh:**

```php
class UserController {
    protected $service;

    public function __construct(UserService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->getAllUsers();
    }
}

```

---

## 2. Vue.js

### 2.1 Reactive Data Binding

Reactive Data Binding adalah konsep yang membuat element UI di perbarui secara otomatis setiap data yang terkait dengan element tersebut berubah. Konsep ini membantu pengembangan front-end lebih sederhana dan efisien, karena pengembang tidak perlu menulis kode tambahan untuk memperbarui DOM secara manual.

### 2.2 Perbedaan Computed Properties dan Methods

- **Computed Properties**: Mengembalikan nilai yang dihitung berdasarkan dependensi reaktif. Hanya dihitung ulang saat dependensi berubah.
- **Methods**: Fungsi biasa yang dipanggil setiap kali digunakan, tanpa cache.

**Contoh:**

```jsx
computed: {
    fullName() {
        return `${this.firstName} ${this.lastName}`;
    }
},
methods: {
    getFullName() {
        return `${this.firstName} ${this.lastName}`;
    }
}

```

### 2.3 Keuntungan Menggunakan Vuex

Vuex adalah libary state management yang dirancang khusus untuk digunakan dengan **Vue.js.** Vuex biasanya sdingunakan ketika aplikasi berkembang lebih besar dan banyak komponen yang berbagi data. Berikut keuntungan penggunaan Vuex  :

- State Management Terpusat

     Dengan state yang dikelola secara terpusat, data yang diakses oleh komponen aplikasi akan tetap konsisten, karena state yang diperbarui hanya ada di satu tempat.

- Mendukung debug dan pelacakan perubahan state.

    Vuex memiliki integrasi dengan Vue Devtools, yang memungkinkan pengembang untuk memantau state, melakukan pelacakan perubahan state, dan memudahkan debugging.

- Reusabilitas dan Pemeliharaan Kode

    Struktur Vuex yang terpusat membuat kode lebih terstruktur dan lebih mudah dibaca dan memudahkan pengelolaan aplikasi dalam jangka panjang, karena semua logika state dikelola di satu tempat.

---

## 3. MySQL

### 3.1 Indexing di MySQL

Indexing adalah struktur data yang mempercepat operasi pencarian pada tabel database. Index dapat meningkatkan performa query SELECT tetapi dapat memperlambat operasi INSERT dan UPDATE.

### 3.2 Perbedaan INNER JOIN dan LEFT JOIN

- **INNER JOIN**: Mengembalikan baris yang cocok di kedua tabel.
- **LEFT JOIN**: Mengembalikan semua baris dari tabel kiri dan baris yang cocok dari tabel kanan.

**Contoh:**

```sql
-- INNER JOIN
SELECT users.name, orders.amount
FROM users
INNER JOIN orders ON users.id = orders.user_id;

-- LEFT JOIN
SELECT users.name, orders.amount
FROM users
LEFT JOIN orders ON users.id = orders.user_id;

```

### 3.3 Normalisasi Database

Normalisasi bertujuan untuk mengurangi redundansi data dan memastikan integritas data.

**Kelemahan:**
Terlalu banyak normalisasi dapat mengakibatkan query menjadi kompleks dan memperlambat performa.

---
