# Jawaban Query Database MySQL
## Kode Yang Diperbaiki
```php
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'customer_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->customer_name = $request->customer_name;
            $order->order_date = $request->order_date;
            $order->total_price = 0;
            $order->save();

            $totalPrice = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['error' => 'Insufficient stock for product'], 400);
                }

                $subtotal = $item['quantity'] * $product->price;
                $totalPrice += $subtotal;

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->subtotal = $subtotal;
                $orderItem->save();

                $product->stock -= $item['quantity'];
                $product->save();
            }

            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();

            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while processing the order'], 500);
        }
    }
}

```
## 1. Identifikasi dan Perbaiki Kesalahan
### a. Logika Bisnis yang Salah

- **Masalah**: `order_date` tidak sesuai dengan input pengguna.
- **Solusi**:  `order_date` diambil dari input pengguna, bukan menggunakan tanggal saat ini.

### b. Perhitungan Subtotal

- **Masalah**: Tidak ada validasi subtotal (harga per unit * jumlah).
- **Solusi**: Tambahkan perhitungan subtotal untuk setiap item, dihitung berdasarkan `quantity * price`.

### c. Penanganan Rollback

- **Masalah**: Tidak ada rollback jika salah satu item gagal disimpan.
- **Solusi**: Gunakan transaksi database untuk melakukan rollback jika ada kesalahan. Contoh:

    ```php
    DB::beginTransaction();
    if ($gagal) {
        DB::rollBack();
    } else {
        DB::commit();
    }
    ```


### d. Penanganan Stok Produk

- **Masalah**: Jika stok tidak mencukupi untuk salah satu item, stok yang sudah dikurangi pada produk sebelumnya tidak dikembalikan.
- **Solusi**: Gunakan `DB::rollBack()` untuk membatalkan semua perubahan jika stok tidak mencukupi.

### e. Struktur Kode

- **Masalah**: Tidak ada transaksi database untuk memastikan konsistensi data jika terjadi kegagalan.
- **Solusi**: Gunakan `DB::beginTransaction()`, `DB::commit()`, dan `DB::rollBack()` untuk transaksi, sehingga data tetap konsisten.

## 2. Tambahkan Validasi dan Fitur

### a. Validasi untuk Items Tidak Boleh Kosong

- **Solusi**: Ditambahkan aturan validasi `min:1` untuk memastikan `items` tidak kosong.

### b. Perhitungan Subtotal untuk Setiap Item

- **Solusi**: Hitung subtotal sebagai `quantity * price` dan simpan pada setiap entitas `OrderItem`.

### c. Perhitungan Total Harga Pesanan

- **Solusi**: DiHitung toal harga pesanan dari subtotal semua item dan simpan pada entitas `Order`.

### d. Penanganan Kesalahan dengan Rollback

- **Solusi**: Menggunakan transaksi database (misalnya `DB::beginTransaction()`, `DB::commit()`, `DB::rollBack()`) untuk memastikan bahwa semua perubahan dibatalkan jika terjadi kegagalan.

## 3. Tambahkan Middleware untuk Autentikasi JWT

### a. Penggunaan Middleware untuk Keamanan

- **Solusi**: Menggunakan middleware `auth:api` untuk memastikan endpoint hanya bisa diakses oleh pengguna yang sudah login.

    ```php
    Route::middleware('auth:api')->group(function () {
        // Daftar route yang dilindungi
    });
    ```
