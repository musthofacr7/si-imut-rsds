<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Welcome to CodeIgniter 4 with Bootstrap 5
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="card text-center">
        <div class="card-header">
            Featured
        </div>
        <div class="card-body">
            <h5 class="card-title">CodeIgniter 4 + Bootstrap 5</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
        <div class="card-footer text-muted">
            2 days ago
        </div>
    </div>
</div>
<?= $this->endSection() ?>
