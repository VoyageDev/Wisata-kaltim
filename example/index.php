<?php
include 'confiq/koneksi.php';
include 'confiq/functions.php';
include 'confiq/auth.php';

$artikel = getArtikel();
$wisata = getWisata();
$kota = getKota();

// Ambil Top Destinasi Wisata (yang is_top = 1)
$wisata_db = mysqli_query($db, "
    SELECT w.*, k.nama as nama_kategori, 
           COUNT(u.id) as jumlah_ulasan,
           COALESCE(AVG(u.nilai), 0) as rating_aktual
    FROM wisata w 
    LEFT JOIN kategori k ON w.kategori_id = k.id 
    LEFT JOIN ulasan u ON w.id = u.wisata_id
    WHERE w.is_top = 1
    GROUP BY w.id
    ORDER BY rating_aktual DESC, w.id DESC
    LIMIT 6
");

// Statistik untuk halaman utama
$stat_total_wisata = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM wisata"))['total'];
$stat_pulau_eksotis = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM wisata WHERE kategori_id = 2"))['total']; // Wisata Bahari
$stat_rating_avg = mysqli_fetch_assoc(mysqli_query($db, "SELECT COALESCE(AVG(nilai), 0) as avg_rating FROM ulasan"))['avg_rating'];

// Ambil artikel dari database
$artikel_db = mysqli_query($db, "SELECT * FROM artikel ORDER BY dibuat_pada DESC LIMIT 4");

// Kota-kota di Kaltim
$kota_kaltim = [
    ['nama' => 'Samarinda', 'icon' => 'fa-city', 'deskripsi' => 'Ibukota Provinsi'],
    ['nama' => 'Balikpapan', 'icon' => 'fa-industry', 'deskripsi' => 'Kota Minyak'],
    ['nama' => 'Berau', 'icon' => 'fa-umbrella-beach', 'deskripsi' => 'Surga Bahari'],
    ['nama' => 'Kutai Kartanegara', 'icon' => 'fa-landmark', 'deskripsi' => 'Kota Bersejarah'],
    ['nama' => 'Bontang', 'icon' => 'fa-leaf', 'deskripsi' => 'Kota Industri Hijau'],
    ['nama' => 'Tenggarong', 'icon' => 'fa-crown', 'deskripsi' => 'Kota Kerajaan']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pariwisata Kalimantan Timur - Jelajahi Keindahan Alam Borneo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="style.css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-palm-tree"></i> Wisata Kaltim
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda"><i class="fas fa-home"></i> Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#wisata"><i class="fas fa-map-marked-alt"></i> Wisata</a></li>
                    <li class="nav-item"><a class="nav-link" href="#artikel"><i class="fas fa-newspaper"></i> Artikel</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kota"><i class="fas fa-city"></i> Kota</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item"><a class="nav-link" href="admin/dashboard.php" title="Admin Panel"><i class="fas fa-user-shield"></i></a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn-login-nav" href="login.php" title="Login"><i class="fas fa-user-circle fa-lg"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="beranda" style="background-image: url('https://i.pinimg.com/1200x/0a/19/97/0a1997811bb137f776487367d8352da4.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 style="margin-top: 3rem;">Jelajahi Keindahan Kalimantan Timur</h1>
            <p>Temukan pesona alam tropis, kekayaan budaya, dan pengalaman ekowisata yang tak terlupakan di jantung Borneo</p>
            
            <!-- Search Box -->
            <div class="search-box mx-auto mb-4" style="max-width: 600px;">
                <form action="wisata_kota.php" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-lg" placeholder="Cari destinasi wisata..." style="border-radius: 25px 0 0 25px; border: none; padding: 15px 25px;">
                    <button type="submit" class="btn btn-search" style="border-radius: 0 25px 25px 0; padding: 0 35px; border: none; font-weight: 600;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-card">
                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h3><?php echo $stat_total_wisata; ?>+</h3>
                        <p>Destinasi Wisata</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-card">
                        <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
                        <h3><?php echo $stat_pulau_eksotis; ?>+</h3>
                        <p>Pulau Eksotis</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h3>100K+</h3>
                        <p>Wisatawan/Tahun</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stats-card">
                        <div class="icon"><i class="fas fa-star"></i></div>
                        <h3><?php echo number_format($stat_rating_avg, 1); ?></h3>
                        <p>Rating Rata-rata</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Wisata Section -->
    <section class="section-cream" id="wisata">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up"><i class="fas fa-fire"></i> Top Destinasi Wisata</h2>
            <div class="row">
                <?php 
                $index = 0;
                while ($w = mysqli_fetch_assoc($wisata_db)): 
                    // Default image jika gambar kosong
                    $gambar = !empty($w['gambar']) ? $w['gambar'] : 'https://i.pinimg.com/736x/f6/56/b3/f656b3b2acb82ed073df85dbfe60923d.jpg';
                    // Gunakan rating aktual dari ulasan (0 jika belum ada ulasan)
                    $rating = floatval($w['rating_aktual']);
                ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="card h-100">
                        <div class="card-img-wrapper">
                            <img src="<?php echo htmlspecialchars($gambar); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($w['nama']); ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($w['nama']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($w['deskripsi']), 0, 100) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="rating">
                                    <?php 
                                    $full_stars = floor($rating);
                                    $half_star = ($rating > 0 && ($rating - $full_stars) >= 0.5) ? 1 : 0;
                                    $empty_stars = 5 - $full_stars - $half_star;
                                    
                                    // Full stars
                                    for ($i = 0; $i < $full_stars; $i++) {
                                        echo '<i class="fas fa-star text-warning"></i>';
                                    }
                                    // Half star
                                    if ($half_star) {
                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                    }
                                    // Empty stars
                                    for ($i = 0; $i < $empty_stars; $i++) {
                                        echo '<i class="far fa-star text-warning"></i>';
                                    }
                                    ?>
                                    <span class="ms-1"><?php echo number_format($rating, 1); ?></span>
                                </div>
                                <span class="badge badge-gold">
                                    <i class="fas fa-map-pin"></i> <?php echo htmlspecialchars($w['kota']); ?>
                                </span>
                            </div>
                            <a href="detail_wisata.php?id=<?php echo $w['id']; ?>" class="btn btn-custom btn-sm w-100">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    $index++;
                endwhile; 
                ?>
            </div>
        </div>
    </section>

    <!-- Artikel Section -->
    <section class="section-white" id="artikel">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up"><i class="fas fa-newspaper"></i> Artikel Wisata Terbaru</h2>
            <div class="row">
                <?php 
                $index = 0;
                while ($a = mysqli_fetch_assoc($artikel_db)): 
                    // Default image jika gambar kosong
                    $gambar = !empty($a['gambar']) ? $a['gambar'] : 'https://i.pinimg.com/736x/f6/56/b3/f656b3b2acb82ed073df85dbfe60923d.jpg';
                    // Check if gambar is a local file or URL
                    if (!empty($a['gambar']) && strpos($a['gambar'], 'http') !== 0) {
                        $gambar = $a['gambar']; // local path
                    }
                    // Link artikel - gunakan link external jika ada
                    $link = !empty($a['link_artikel']) ? $a['link_artikel'] : '#';
                    $target = !empty($a['link_artikel']) ? '_blank' : '_self';
                ?>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="article-card h-100">
                        <div class="overflow-hidden">
                            <img src="<?php echo htmlspecialchars($gambar); ?>" alt="<?php echo htmlspecialchars($a['judul']); ?>">
                        </div>
                        <div class="content">
                            <span class="source"><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($a['dibuat_pada'])); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($a['judul']); ?></h5>
                            <a href="<?php echo htmlspecialchars($link); ?>" target="<?php echo $target; ?>" class="btn btn-custom btn-sm w-100 mt-2">
                                <i class="fas fa-external-link-alt"></i> Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    $index++;
                endwhile; 
                ?>
            </div>
        </div>
    </section>

    <!-- Kota Section -->
    <section class="section-cream" id="kota">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up"><i class="fas fa-city"></i> Kota-Kota Populer</h2>
            <div class="row">
                <?php foreach ($kota_kaltim as $index => $k): ?>
                <div class="col-lg-2 col-md-4 col-6 mb-4" data-aos="zoom-in" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <a href="wisata_kota.php?kota=<?php echo urlencode($k['nama']); ?>" class="text-decoration-none">
                        <div class="card text-center h-100" style="padding: 1.5rem;">
                            <i class="fas <?php echo $k['icon']; ?> fa-3x mb-3" style="color: var(--brown);"></i>
                            <h6 class="card-title mb-1"><?php echo $k['nama']; ?></h6>
                            <small class="text-muted"><?php echo $k['deskripsi']; ?></small>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero" style="padding: 80px 0;">
        <div class="container text-center" data-aos="zoom-in">
            <h2 class="mb-4">Siap Menjelajahi Kalimantan Timur?</h2>
            <p class="mb-4">Daftar sekarang dan mulai petualangan tak terlupakan Anda!</p>
            <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="btn btn-custom btn-lg me-3">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
            <?php endif; ?>
            <a href="#wisata" class="btn btn-custom btn-lg" style="background: transparent; border: 2px solid var(--cream-light);">
                <i class="fas fa-compass"></i> Jelajahi Wisata
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <h5 class="footer-title"><i class="fas fa-palm-tree"></i> Wisata Kaltim</h5>
                    <p>Platform terpercaya untuk menjelajahi keindahan alam dan budaya Kalimantan Timur. Temukan destinasi impian Anda bersama kami.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="footer-title">Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="#beranda" class="footer-link"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                        <li><a href="#wisata" class="footer-link"><i class="fas fa-chevron-right"></i> Wisata</a></li>
                        <li><a href="#artikel" class="footer-link"><i class="fas fa-chevron-right"></i> Artikel</a></li>
                        <li><a href="#kota" class="footer-link"><i class="fas fa-chevron-right"></i> Kota</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="footer-title">Destinasi Populer</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link"><i class="fas fa-chevron-right"></i> Kepulauan Derawan</a></li>
                        <li><a href="#" class="footer-link"><i class="fas fa-chevron-right"></i> Labuan Cermin</a></li>
                        <li><a href="#" class="footer-link"><i class="fas fa-chevron-right"></i> Bukit Bangkirai</a></li>
                        <li><a href="#" class="footer-link"><i class="fas fa-chevron-right"></i> Pulau Maratua</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="footer-title">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color: var(--gold);"></i> Samarinda, Kalimantan Timur</li>
                        <li class="mb-2"><i class="fas fa-phone me-2" style="color: var(--gold);"></i> +62 852 5201 4600</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2" style="color: var(--gold);"></i> info@wisatakaltim.id</li>
                    </ul>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center py-3">
                <p class="mb-0">&copy; 2026 Wisata Kalimantan Timur. Dibuat dengan <i class="fas fa-heart" style="color: var(--gold);"></i> di Indonesia</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const backToTop = document.getElementById('backToTop');
            
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 30px rgba(93, 78, 55, 0.4)';
            } else {
                navbar.style.boxShadow = '0 4px 20px rgba(93, 78, 55, 0.25)';
            }
            
            // Show/hide back to top button
            if (window.scrollY > 300) {
                backToTop.style.display = 'flex';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        // Back to top click
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    
    <!-- Back to Top Button -->
    <button id="backToTop" style="display: none; position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--gold), var(--gold-light)); border: none; color: var(--brown-dark); font-size: 1.2rem; cursor: pointer; box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4); align-items: center; justify-content: center; z-index: 999; transition: all 0.3s ease;">
        <i class="fas fa-arrow-up"></i>
    </button>
</body>
</html>
