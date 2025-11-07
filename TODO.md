# TODO: Implementasi Sistem Jadwal Berbasis Hari Keberangkatan

## Database Migration
- [x] Buat migration baru: ubah kolom 'tanggal' menjadi 'hari_keberangkatan' (enum), hapus 'day_offset'

## Model Updates
- [x] Update app/Models/Jadwal.php: fillable, casts, hapus accessor dynamicTanggal, tambah method getUpcomingDates()

## Controller Updates
- [x] Update app/Http/Controllers/JadwalController.php: method index untuk hitung tanggal-tanggal mendatang per jadwal

## Admin Views
- [x] Update resources/views/admin/jadwals/create.blade.php: ganti input tanggal dengan select hari
- [x] Update resources/views/admin/jadwals/edit.blade.php: ganti input tanggal dengan select hari

## User Views
- [x] Update resources/views/user/jadwal.blade.php: tampilkan multiple tanggal per jadwal

## Cleanup
- [x] Hapus app/Console/Commands/UpdateJadwalDates.php
- [x] Update routes/console.php jika perlu

## Testing
- [x] Migration berhasil dijalankan
- [ ] Test input admin hari keberangkatan
- [ ] Test tampilan user tanggal-tanggal yang benar
- [ ] Test booking functionality
- [ ] Test performa query
