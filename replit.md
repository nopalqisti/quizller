# Quizller - Aplikasi Kuis Online

## Overview
Quizller adalah aplikasi kuis online berbasis PHP native dengan sistem 3 role (Admin, Teacher, Student). Aplikasi ini memungkinkan teacher membuat quiz, student mengerjakan quiz, dan admin mengelola seluruh sistem.

## Fitur Utama

### Admin
- Mengelola Teacher (tambah, hapus)
- Mengelola Student (tambah, hapus)
- Mengelola Kelas (tambah, hapus)
- Melihat semua Quiz yang dibuat oleh semua Teacher
- Melihat hasil quiz lengkap dengan statistik dan detail jawaban student

### Teacher
- Registrasi mandiri
- Membuat quiz baru untuk kelas tertentu
- Menambah soal ke quiz (bank soal)
- Melihat daftar quiz yang dibuat
- Melihat hasil quiz student dengan statistik lengkap
- Melihat detail jawaban student per soal (benar/salah)

### Student
- Login menggunakan ID Student dan Password (tanpa role number)
- Melihat daftar quiz yang tersedia untuk kelasnya
- Mengerjakan quiz dengan pilihan ganda A-D
- Melihat hasil quiz dengan:
  - Total skor dan persentase
  - Detail jawaban benar/salah per soal
  - Grafik statistik (chart.js)

## Teknologi yang Digunakan
- **Backend:** PHP 8.2 Native (tanpa framework)
- **Database:** SQLite
- **Frontend:** HTML, CSS, JavaScript
- **Chart Library:** Chart.js (untuk visualisasi grafik)
- **Server:** PHP Built-in Server

## Struktur Database

### Tables:
1. **admins** - Data admin sistem
2. **teachers** - Data teacher/pengajar
3. **students** - Data student dengan student_id
4. **classes** - Data kelas
5. **tests** - Data quiz/ujian
6. **questions** - Bank soal
7. **question_test_mapping** - Mapping soal ke quiz
8. **student_answers** - Jawaban student per soal
9. **student_test_results** - Hasil quiz student
10. **status** - Status quiz (PENDING, RUNNING, COMPLETED)

## Sample Data

### Admin (4 users)
- Username: opal, ardi, fina, rifki
- Password: admin123

### Teacher (5 users)
- Email: budi@teacher.com, siti@teacher.com, ahmad@teacher.com, dewi@teacher.com, eko@teacher.com
- Password: teacher123

### Student (20 users)
- ID: S001 - S020
- Password: student123
- Nama: Nama-nama Indonesia (Andi Wijaya, Rina Kusuma, dll)

## Cara Menjalankan
1. Server PHP sudah dikonfigurasi di workflow
2. Akses aplikasi melalui webview
3. Pilih role login (Admin/Teacher/Student)
4. Gunakan kredensial sample data di atas

## File Structure
```
/
├── admin/              # Halaman admin
├── teacher/            # Halaman teacher
├── student/            # Halaman student
├── assets/
│   └── css/           # File CSS
├── includes/          # Config dan functions
├── database/          # Database dan init script
├── index.php          # Landing page
├── admin-login.php    # Login admin
├── teacher-login.php  # Login teacher
├── student-login.php  # Login student
├── teacher-register.php # Registrasi teacher
└── logout.php         # Logout
```

## Perubahan dari Project Original
1. ✅ Menghapus role number untuk student login
2. ✅ Menambahkan role Teacher dengan kemampuan membuat quiz
3. ✅ Memisahkan role Admin dan Teacher
4. ✅ Teacher dapat registrasi mandiri
5. ✅ Halaman hasil quiz dengan detail jawaban benar/salah
6. ✅ Statistik dan grafik performa (Chart.js)
7. ✅ Database dengan sample data nama Indonesia
8. ✅ Password sederhana untuk testing

## Status
Project: ✅ Completed
Last Updated: October 19, 2025
