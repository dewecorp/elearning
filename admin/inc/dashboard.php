<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-lg-3 col-md-6">
        <div class="modern-card stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number">
                <?php
                $siswa = mysqli_query($db, "SELECT * FROM tb_siswa");
                echo mysqli_num_rows($siswa);
                ?>
            </div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="modern-card stat-card">
            <div class="stat-icon success">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-number">
                <?php
                $pengajar = mysqli_query($db, "SELECT * FROM tb_pengajar");
                echo mysqli_num_rows($pengajar);
                ?>
            </div>
            <div class="stat-label">Total Pengajar</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="modern-card stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="stat-number">
                <?php
                $kelas = mysqli_query($db, "SELECT * FROM tb_kelas");
                echo mysqli_num_rows($kelas);
                ?>
            </div>
            <div class="stat-label">Total Kelas</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="modern-card stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number">
                <?php
                $mapel = mysqli_query($db, "SELECT * FROM tb_mapel");
                echo mysqli_num_rows($mapel);
                ?>
            </div>
            <div class="stat-label">Total Mapel</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Welcome Card -->
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="card-body p-4">
                <h4 class="mb-3">
                    <i class="fas fa-home text-primary"></i>
                    Selamat Datang, <strong><?php echo ucfirst($data_terlogin['username']); ?></strong>!
                </h4>
                <p class="text-muted mb-4">
                    Selamat datang kembali di Dashboard E-Learning. Kelola pembelajaran online dengan efisien dan modern.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="mb-3">Quick Actions:</h6>
                        <div class="d-grid gap-2">
                            <a href="?page=kelas" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Kelas
                            </a>
                            <a href="?page=pengajar" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-user-plus"></i> Tambah Pengajar
                            </a>
                            <a href="?page=siswa" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-user-graduate"></i> Tambah Siswa
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">System Info:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-database text-primary"></i> Database: Connected</li>
                            <li><i class="fas fa-user-check text-success"></i> Login as: <?php echo ucfirst($level); ?></li>
                            <li><i class="fas fa-clock text-warning"></i> Server: <?php echo date('H:i'); ?></li>
                            <li><i class="fas fa-calendar text-info"></i> Tanggal: <?php echo date('d M Y'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Card -->
    <div class="col-lg-4">
        <div class="modern-card">
            <div class="card-body p-4">
                <h5 class="mb-3">
                    <i class="fas fa-chart-line text-primary"></i>
                    Quick Access
                </h5>
                <div class="d-grid gap-2">
                    <a href="?page=pengaturan" class="btn btn-primary-modern">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                    <a href="?page=berita" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-newspaper"></i> Kelola Berita
                    </a>
                    <a href="?page=materi" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-file-alt"></i> Kelola Materi
                    </a>
                    <a href="?page=quiz" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-tasks"></i> Kelola Tugas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>