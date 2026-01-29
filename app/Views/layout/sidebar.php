<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url() ?>" class="brand-link">
            <span class="brand-text fw-light">IMUT RSDS</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if (in_groups('administrator')) : ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('users') ?>" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('jenis-indikator') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Jenis Indikator</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('master-satuan-indikator') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Master Satuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('indikator-mutu') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Indikator Mutu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('area-pengukuran') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Area Pengukuran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('setting-indikator-mutu') ?>" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Setting Indikator Mutu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('mapping-indikator') ?>" class="nav-link">
                                <i class="nav-icon bi bi-map"></i>
                                <p>Mapping Indikator</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Rekap Indikator Mutu
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('rekap-indikator-mutu') ?>" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Rekap Indikator Mutu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('rekap-jenis-indikator') ?>" class="nav-link">
                                <i class="nav-icon bi bi-list-task"></i>
                                <p>Rekap by Jenis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('rekap-area-pengukuran') ?>" class="nav-link">
                                <i class="nav-icon bi bi-geo-alt"></i>
                                <p>Rekap by Area</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php endif; ?>

                <?php if (in_groups('pj-mutu')) : ?>
                <li class="nav-item">
                    <a href="<?= base_url('input-indikator-mutu') ?>" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>Input Indikator Mutu</p>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= base_url('pdsa') ?>" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>PDSA</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
