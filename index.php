<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Quizller</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* CSS Tambahan Khusus Halaman Depan agar mirip Dashboard */
        body {
            /* Memastikan background terang sesuai tema dashboard */
            background-color: #f3f4f6; 
            min-height: 100vh;
        }

        /* Menimpa style navbar dari style.css utama agar spesifik untuk halaman ini jika perlu, 
           tapi idealnya gunakan style.css utama yang sudah diperbaiki. 
           Kode di bawah ini memastikan navbar gradien muncul. */
        .navbar-index {
            background: linear-gradient(135deg, #667eea); /* Gradien Ungu/Biru Dashboard */
            padding: 15px 30px;
            border-radius: 16px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .brand-container {
            display: flex;
            align-items: center;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            backdrop-filter: blur(5px);
        }

        .navbar-index h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }

        /* Area Konten Utama */
        .hero-section {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 50px auto;
        }

        .hero-section h2 {
            font-size: 32px;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .hero-section p.intro {
            font-size: 18px;
            color: #6b7280;
            line-height: 1.6;
        }

        /* Grid Kartu Peran (Mirip Dashboard Grid) */
        .role-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 35px 25px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #1f2937;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        /* Icon wrapper meniru gaya dashboard */
        .role-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }

        .role-icon-wrapper svg {
            width: 40px;
            height: 40px;
            stroke-width: 1.5;
        }

        /* Warna khusus per peran */
        .role-admin .role-icon-wrapper { background-color: #dbeafe; color: #2563eb; }
        .role-teacher .role-icon-wrapper { background-color: #dcfce7; color: #16a34a; }
        .role-student .role-icon-wrapper { background-color: #f3f4f6; color: #6b7280; }

        .role-info h3 {
            font-size: 22px;
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        .role-info p {
            margin: 0;
            color: #6b7280;
            font-size: 15px;
        }

        .footer-text {
            margin-top: 40px;
            color: #6b7280;
            text-align: center;
            font-size: 16px;
        }

        .footer-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar-index">
            <div class="brand-container">
                <div class="brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.101.433-.101.66 0 .77.409 1.447 1.034 1.863a4.485 4.485 0 00-1.448 2.39c-.125.65-.406 1.23-.831 1.707-.584.655-1.413.987-2.287.987a2.25 2.25 0 01-2.25-2.25v-4.5c0-1.135.845-2.098 1.976-2.192.617-.049 1.251-.08 1.902-.08 1.132 0 2.176.455 2.922 1.188z" />
                    </svg>
                </div>
                <h1>Quizller</h1>
            </div>
        </div>

        <div class="hero-section">
            <h2>Selamat Datang di Platform Ujian Modern</h2>
            <p class="intro">
                Platform yang memudahkan guru dalam membuat soal dan siswa dalam mengerjakan ujian di mana saja dan kapan saja. Silakan pilih peran Anda untuk memulai.
            </p>
        </div>

        <div class="role-cards-grid">
            <a href="admin-login.php" class="role-card role-admin">
                <div class="role-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="role-info">
                    <h3>Admin</h3>
                    <p>Kelola data master, pengguna, dan sistem secara keseluruhan.</p>
                </div>
            </a>
            
            <a href="teacher-login.php" class="role-card role-teacher">
                <div class="role-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div class="role-info">
                    <h3>Guru</h3>
                    <p>Buat soal, kelola ujian, dan pantau hasil nilai siswa.</p>
                </div>
            </a>
            
            <a href="student-login.php" class="role-card role-student">
                <div class="role-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div class="role-info">
                    <h3>Siswa</h3>
                    <p>Akses dan kerjakan ujian yang telah tersedia untuk kelas Anda.</p>
                </div>
            </a>
        </div>

        <p class="footer-text">
            Belum memiliki akun Guru? 
            <a href="teacher-register.php">Daftar di sini</a>
        </p>
    </div>
</body>
</html>