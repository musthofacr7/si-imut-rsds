<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $this->renderSection('title') ?> | IMUT RSDS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="IMUT RSDS">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, adminlte, adminlte v4, adminlte v4.0.0-beta1">
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/img/logo-rsud.jpg') ?>">

    <!-- Fonts -->
    <link rel="stylesheet" href="<?= base_url('assets/fonts/source-sans-3/index.css') ?>">

    <!-- Third Party Plugin(OverlayScrollbars) -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/overlayscrollbars/styles/overlayscrollbars.min.css') ?>">

    <!-- Third Party Plugin(Bootstrap Icons) -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-icons/font/bootstrap-icons.min.css') ?>">

    <!-- Required Plugin(AdminLTE) -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/css/adminlte.min.css') ?>">
    
    <!-- Select2 CSS -->
    <link href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/select2/css/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet" />
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/css/responsive.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/css/buttons.bootstrap5.min.css') ?>">
    
    <!-- Summernote CSS -->
    <link href="<?= base_url('assets/plugins/summernote/summernote-bs5.min.css') ?>" rel="stylesheet">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        <?= $this->include('layout/navbar') ?>
        <!-- Sidebar -->
        <?= $this->include('layout/sidebar') ?>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= $this->renderSection('title') ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">
                Anything you want
            </div>
            <strong>Copyright &copy; 2025 <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <!-- jQuery (must be loaded first) -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    
    <!-- Third Party Plugin(OverlayScrollbars) -->
    <script src="<?= base_url('assets/plugins/overlayscrollbars/js/overlayscrollbars.browser.es6.min.js') ?>"></script>
    
    <!-- Required Plugin(Popperjs for Bootstrap 5) -->
    <script src="<?= base_url('assets/plugins/popper/popper.min.js') ?>"></script>
    
    <!-- Required Plugin(Bootstrap 5) -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    
    <!-- Required Plugin(AdminLTE) -->
    <script src="<?= base_url('assets/plugins/adminlte/js/adminlte.min.js') ?>"></script>
    
    <!-- Select2 JS -->
    <script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
    
    <!-- Summernote JS -->
    <script src="<?= base_url('assets/plugins/summernote/summernote-bs5.min.js') ?>"></script>

    <!-- DataTables & Plugins -->
    <script src="<?= base_url('assets/plugins/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/responsive.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/buttons.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/jszip.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/pdfmake.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/vfs_fonts.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/buttons.html5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/buttons.print.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/js/buttons.colVis.min.js') ?>"></script>
    
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
