<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Login | IMUT RSDS</title>
    <link rel="icon" href="<?= base_url('assets/img/logo-rsud.jpg') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/overlayscrollbars/styles/overlayscrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/css/adminlte.min.css') ?>">
    <style>
        body.login-page::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?= base_url('assets/img/bg-login.jpg') ?>');
            background-size: cover;
            background-position: center;
            opacity: 0.3; /* 70% transparent */
            z-index: -1;
        }
    </style>
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo">
            <a href="#">
                <img src="<?= base_url('assets/img/logo-rsud.jpg') ?>" alt="Logo RSUD Soedirman" height="100" class="mb-2" style="mix-blend-mode: multiply;">
                <br>
                <b>IMUT</b>RSDS
            </a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?=lang('Auth.loginTitle')?></p>

                <?= view('Myth\Auth\Views\_message_block') ?>

                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

<?php if ($config->validFields === ['email']): ?>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                               name="login" placeholder="<?=lang('Auth.email')?>">
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= session('errors.login') ?>
                        </div>
                    </div>
<?php else: ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                               name="login" placeholder="<?=lang('Auth.emailOrUsername')?>">
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= session('errors.login') ?>
                        </div>
                    </div>
<?php endif; ?>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.password')?>">
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        <div class="invalid-feedback">
                            <?= session('errors.password') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
<?php if ($config->allowRemembering): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                <label class="form-check-label" for="remember">
                                    <?=lang('Auth.rememberMe')?>
                                </label>
                            </div>
<?php endif; ?>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary"><?=lang('Auth.loginAction')?></button>
                            </div>
                        </div>
                    </div>
                </form>

<?php if ($config->allowRegistration) : ?>
                <p class="mb-1">
                    <a href="<?= url_to('register') ?>"><?=lang('Auth.needAnAccount')?></a>
                </p>
<?php endif; ?>
<?php if ($config->activeResetter): ?>
                <p class="mb-0">
                    <a href="<?= url_to('forgot') ?>"><?=lang('Auth.forgotYourPassword')?></a>
                </p>
<?php endif; ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/overlayscrollbars/js/overlayscrollbars.browser.es6.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/popper/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/adminlte/js/adminlte.min.js') ?>"></script>
</body>
</html>
