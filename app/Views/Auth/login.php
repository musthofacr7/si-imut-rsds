<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Login | IMUT RSDS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQZAdnN1iOx72037vGt1Vzp/d8hZ9jvnIlCqU=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css" crossorigin="anonymous">
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>IMUT</b>RSDS</a>
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

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+w76GZz9gS+IW9vG7aaThZ+tS8P15qIqyT969Kuu=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js" crossorigin="anonymous"></script>
</body>
</html>
