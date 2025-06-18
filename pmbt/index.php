<?php
// pmbt/index.php
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Nusantara Heritage - Budaya Indonesia</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <link rel="stylesheet" href="../css/index.css" /> 
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-circle me-2" style="color: #DC143C;"></i>
                Nusantara Heritage
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                Menu <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Beranda</a></li>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" role="button" data-bs-toggle="dropdown">Budaya</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="batik.html">Batik</a></li>
                                <li><a class="dropdown-item" href="musik.html">Alat Musik</a></li>
                                <li><a class="dropdown-item" href="tari.html">Tari Tradisional</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="sejarah.html">Sejarah</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.html">Kontak</a></li>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="admin.php">Admin Dashboard</a></li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-lg-3 py-3 py-lg-4" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text"><small class="text-muted">Role: <?php echo ucfirst(htmlspecialchars($_SESSION['role'])); ?></small></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <header class="masthead hero-gradient d-flex align-items-center">
        <div class="batik-pattern position-absolute w-100 h-100"></div>
        <div class="batik-decorative position-absolute w-100 h-100" style="opacity: 0.3;"></div>
        <div class="floating-elements">
            <div class="floating-element"><i class="fas fa-star text-warning" style="font-size: 2rem;"></i></div>
            <div class="floating-element"><i class="fas fa-circle text-warning" style="font-size: 1.5rem;"></i></div>
            <div class="floating-element"><i class="fas fa-diamond text-warning" style="font-size: 1.8rem;"></i></div>
            <div class="floating-element"><i class="fas fa-heart text-warning" style="font-size: 1.6rem;"></i></div>
        </div>
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading text-center">
                        <h1>Batik Nusantara</h1>
                        <hr class="my-4" style="border-color: #FFD700; border-width: 3px;">
                        <span class="subheading">
                            Keindahan seni tradisional Indonesia yang sarat makna dan filosofi,<br>
                            diwariskan turun-temurun sebagai identitas bangsa
                        </span>
                        <div class="mt-5">
                            <a href="#budaya" class="btn btn-primary btn-lg">
                                <i class="fas fa-compass me-2"></i> Jelajahi Budaya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container px-4 px-lg-5" id="budaya">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-12">
                <div class="text-center mb-5 pt-5">
                    <h2 class="display-4 fw-bold text-dark mb-4">Kekayaan Budaya Indonesia</h2>
                    <p class="lead text-muted">Tiga pilar utama budaya Indonesia yang menawan dunia</p>
                </div>
                <div class="row g-4 mb-5">
                    <div class="col-lg-4">
                        <div class="post-preview card-hover text-center h-100">
                            <div class="culture-icon batik-icon"><i class="fas fa-palette"></i></div>
                            <h3 class="fw-bold mb-3">Batik</h3>
                            <p class="text-muted mb-4">Seni kain tradisional Indonesia yang diakui UNESCO sebagai Warisan Kemanusiaan untuk Budaya Lisan dan Nonbendawi</p>
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">• Motif Parang, Kawung, Mega Mendung</small>
                                <small class="text-muted d-block mb-1">• Teknik tulis dan cap</small>
                                <small class="text-muted d-block">• Filosofi mendalam setiap motif</small>
                            </div>
                            <a href="batik.html" class="btn btn-outline-warning">Pelajari Lebih Lanjut <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="post-preview card-hover text-center h-100">
                            <div class="culture-icon music-icon"><i class="fas fa-music"></i></div>
                            <h3 class="fw-bold mb-3">Alat Musik</h3>
                            <p class="text-muted mb-4">Instrumen musik tradisional yang mencerminkan kearifan lokal dan keharmonisan dengan alam</p>
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">• Gamelan Jawa dan Bali</small>
                                <small class="text-muted d-block mb-1">• Angklung Sunda</small>
                                <small class="text-muted d-block">• Sasando Nusa Tenggara</small>
                            </div>
                            <a href="musik.html" class="btn btn-outline-primary">Pelajari Lebih Lanjut <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="post-preview card-hover text-center h-100">
                            <div class="culture-icon dance-icon"><i class="fas fa-hands"></i></div>
                            <h3 class="fw-bold mb-3">Tari Tradisional</h3>
                            <p class="text-muted mb-4">Ekspresi seni gerak yang mengandung nilai spiritual, estetika, dan makna filosofis yang mendalam</p>
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">• Kecak Bali, Saman Aceh</small>
                                <small class="text-muted d-block mb-1">• Pendet, Legong, Bedhaya</small>
                                <small class="text-muted d-block">• Tari Piring, Tor-tor</small>
                            </div>
                            <a href="tari.html" class="btn btn-outline-danger">Pelajari Lebih Lanjut <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row g-5 align-items-center mb-5 py-5">
                    <div class="col-lg-6">
                        <h2 class="display-5 fw-bold text-dark mb-4">Melestarikan Warisan Leluhur</h2>
                        <p class="lead mb-4">Setiap motif batik, setiap nada gamelan, dan setiap gerakan tari tradisional mengandung makna mendalam yang telah diwariskan turun-temurun. Mari kita jaga dan lestarikan kekayaan budaya ini untuk generasi mendatang.</p>
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                        <i class="fas fa-check fa-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold">Dokumentasi Digital</h5>
                                        <p class="text-muted mb-0">Mengabadikan setiap detail budaya dalam format digital</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                        <i class="fas fa-check fa-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold">Edukasi Generasi Muda</h5>
                                        <p class="text-muted mb-0">Program pembelajaran budaya untuk anak-anak</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                                        <i class="fas fa-check fa-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold">Promosi Global</h5>
                                        <p class="text-muted mb-0">Memperkenalkan budaya Indonesia ke dunia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card h-100 border-0" style="background: linear-gradient(135deg, #DC143C, #FFD700);">
                            <div class="card-body text-white text-center p-5">
                                <h3 class="card-title fw-bold mb-4">Bergabunglah Dengan Kami</h3>
                                <p class="card-text mb-4 fs-5">Jadilah bagian dari gerakan pelestarian budaya Indonesia</p>
                                <a href="login.php" class="btn btn-light btn-lg text-danger fw-bold"><i class="fas fa-user-plus me-2"></i> Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="border-top py-5">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5">
                <div class="col-lg-3 mb-4">
                    <h5 class="text-warning fw-bold mb-3">Nusantara Heritage</h5>
                    <p class="text-light mb-3">Melestarikan dan memperkenalkan kekayaan budaya Indonesia kepada dunia.</p>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Budaya</h6>
                    <ul class="list-unstyled">
                        <li><a href="batik.html" class="text-light text-decoration-none">Batik</a></li>
                        <li><a href="musik.html" class="text-light text-decoration-none">Alat Musik</a></li>
                        <li><a href="tari.html" class="text-light text-decoration-none">Tari Tradisional</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Informasi</h6>
                    <ul class="list-unstyled">
                        <li><a href="sejarah.html" class="text-light text-decoration-none">Tentang Kami</a></li>
                        <li><a href="sejarah.html" class="text-light text-decoration-none">Sejarah</a></li>
                        <li><a href="contact.html" class="text-light text-decoration-none">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Ikuti Kami</h6>
                    <div class="d-flex">
                        <a href="#!" class="social-icon social-facebook text-white text-decoration-none me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#!" class="social-icon social-twitter text-white text-decoration-none me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#!" class="social-icon social-instagram text-white text-decoration-none"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.3);">
            <div class="text-center">
                <div class="small text-light">Copyright &copy; Nusantara Heritage 2025</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/index.js"></script>
</body>
</html>