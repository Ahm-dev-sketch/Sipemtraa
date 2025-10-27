# TODO: Perbaikan Layout Kursi Minibus

## Tugas Utama
- Perbaiki layout kursi di `resources/views/booking/step3.blade.php` agar sesuai dengan bentuk minibus yang sebenarnya
- Kursi supir tidak bisa diklik dan tidak bisa dipilih
- Kursi penumpang: 13 kursi dengan nomor A1-A13

## Detail Layout (Update berdasarkan feedback user)
- **Baris Depan**: Kursi kiri (A1) | Kursi supir (kanan, tidak bisa pilih, ikon steering wheel dengan tulisan "Supir")
- **Baris Belakang 1**: A2 | A3 | A4 | A5 (4 kursi)
- **Baris Belakang 2**: A6 | A7 | A8 | A9 (4 kursi)
- **Baris Belakang 3**: A10 | A11 | A12 | A13 (4 kursi)
- Total kursi penumpang: 13
- Kursi supir: tidak bernomor, tidak bisa dipilih

## Perubahan yang Dibutuhkan
1. Update array `$seats` di `BookingController.php` wizardStep3() ke ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12', 'A13']
2. Update view `step3.blade.php` untuk layout baru:
   - Supir di sebelah kanan depan dengan tulisan "Supir"
   - Kursi A1 di sebelah kiri depan
   - 3 baris belakang, masing-masing 4 kursi (2 di belakang A1 + 2 di belakang supir)
   - Tambahkan legenda untuk kursi supir
   - Pastikan kursi supir tidak bisa diklik

## Langkah Implementasi
1. Edit `app/Http/Controllers/BookingController.php` - update array seats ke A1-A13
2. Edit `resources/views/booking/step3.blade.php` - update layout HTML dan CSS
3. Test layout untuk memastikan tampilan benar dan fungsionalitas pilih kursi masih bekerja

## Status
- [x] Update controller seats array ke A1-A13
- [x] Update view layout dengan supir di kanan dan 4 kursi per baris belakang
- [ ] Test functionality
