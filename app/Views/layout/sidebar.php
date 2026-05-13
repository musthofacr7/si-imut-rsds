<?php $uri1 = service('uri')->getSegment(1); ?>
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
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= $uri1 == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if (in_groups('administrator')) : ?>
                <?php $masterDataMenu = ['users', 'jenis-indikator', 'master-satuan-indikator', 'indikator-mutu', 'area-pengukuran', 'setting-indikator-mutu', 'mapping-indikator']; ?>
                <li class="nav-item <?= in_array($uri1, $masterDataMenu) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($uri1, $masterDataMenu) ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('users') ?>" class="nav-link <?= $uri1 == 'users' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('jenis-indikator') ?>" class="nav-link <?= $uri1 == 'jenis-indikator' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Jenis Indikator</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('master-satuan-indikator') ?>" class="nav-link <?= $uri1 == 'master-satuan-indikator' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Master Satuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('indikator-mutu') ?>" class="nav-link <?= $uri1 == 'indikator-mutu' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Indikator Mutu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('area-pengukuran') ?>" class="nav-link <?= $uri1 == 'area-pengukuran' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Area Pengukuran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('setting-indikator-mutu') ?>" class="nav-link <?= $uri1 == 'setting-indikator-mutu' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Setting Indikator Mutu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('mapping-indikator') ?>" class="nav-link <?= $uri1 == 'mapping-indikator' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-map"></i>
                                <p>Mapping Indikator</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <?php $rekapDataMenu = ['rekap-indikator-mutu', 'rekap-jenis-indikator', 'rekap-area-pengukuran']; ?>
                <li class="nav-item <?= in_array($uri1, $rekapDataMenu) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($uri1, $rekapDataMenu) ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Rekap Indikator Mutu
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('rekap-indikator-mutu') ?>" class="nav-link <?= $uri1 == 'rekap-indikator-mutu' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Rekap Indikator Mutu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('rekap-jenis-indikator') ?>" class="nav-link <?= $uri1 == 'rekap-jenis-indikator' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-list-task"></i>
                                <p>Rekap by Jenis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('rekap-area-pengukuran') ?>" class="nav-link <?= $uri1 == 'rekap-area-pengukuran' ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-geo-alt"></i>
                                <p>Rekap by Area</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('monitoring-input') ?>" class="nav-link <?= $uri1 == 'monitoring-input' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-eye"></i>
                        <p>Monitoring Input</p>
                    </a>
                </li>
                <?php endif; ?>
                
               

                <?php if (in_groups('pj-mutu')) : ?>
                <li class="nav-item">
                    <a href="<?= base_url('input-indikator-mutu') ?>" class="nav-link <?= $uri1 == 'input-indikator-mutu' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>Input Indikator Mutu</p>
                    </a>
                </li>
                <?php endif; ?>
                 <?php if (in_groups('administrator') || in_groups('pj-mutu')) : ?>
                <?php $formulirMenu = ['formulir-ikp', 'investigasi-sederhana', 'daftar-ikp']; ?>
                <li class="nav-item <?= in_array($uri1, $formulirMenu) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($uri1, $formulirMenu) ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-file-earmark-medical"></i>
                        <p>
                            Laporan Insiden (IKP)
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('formulir-ikp/daftar') ?>" class="nav-link <?= ($uri1 == 'formulir-ikp' && service('uri')->getSegment(2) == 'daftar') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Daftar Laporan IKP</p>
                            </a>
                        </li>


                    </ul>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= base_url('pdsa') ?>" class="nav-link <?= $uri1 == 'pdsa' ? 'active' : '' ?>">
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
