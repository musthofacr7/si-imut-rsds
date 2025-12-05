<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Create User
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Create New User</h3>
            </div>
            <form action="<?= base_url('users/store') ?>" method="post">
                <div class="card-body">
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="username" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="role" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">
                            <select class="form-select select2" id="role" name="role" required>
                                <option value="administrator">Administrator</option>
                                <option value="pj-mutu">PJ Mutu</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3" id="area-pengukuran-field" style="display: none;">
                        <label for="area_pengukuran_id" class="col-sm-3 col-form-label">Area Pengukuran</label>
                        <div class="col-sm-9">
                            <select class="form-select select2" id="area_pengukuran_id" name="area_pengukuran_id">
                                <option value="">Pilih Area Pengukuran</option>
                                <?php foreach ($area_pengukuran as $area) : ?>
                                    <option value="<?= $area['id'] ?>"><?= esc($area['area_pengukuran']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="pass_confirm" class="col-sm-3 col-form-label">Confirm Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="<?= base_url('users') ?>" class="btn btn-secondary float-end">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    function toggleAreaField() {
        if ($('#role').val() === 'pj-mutu') {
            $('#area-pengukuran-field').show();
        } else {
            $('#area-pengukuran-field').hide();
        }
    }

    // Initial check
    toggleAreaField();

    // On change
    $('#role').on('change', function() {
        toggleAreaField();
    });
});
</script>
<?= $this->endSection() ?>
