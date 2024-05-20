**CARA MENGGUNAKAN**
1. git clone https://github.com/syahidanAS/JP-SYAHIDAN-PUSDATIN.git
2. composer install
3. Buat database baru 
4. sesuaikan file .env
-CONNECTED_MEDICAL_FACILITY_IHS=https://dinkes.jakarta.go.id/apps/jp-2024/all-rs-terkoneksi.json
-JAKARTA_MEDICAL_FACILITY=https://dinkes.jakarta.go.id/apps/jp-2024/all-rsud.json
-IHS_TRANSACTION=https://dinkes.jakarta.go.id/apps/jp-2024/transaksi-data-satusehat.json

7. php artisan serve
8. Proyek ini menggunakan Vite untuk menjalankan Tailwindcss, silahkan ketikkan perintah: npm install dan npm run dev



**MENGGUNAKAN API**
GET Daftar RSUD yang telah di merge http://localhost:8000/api/merge-api-rsud

