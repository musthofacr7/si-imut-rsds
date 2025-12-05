<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
User Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">User List</h3>
        <div class="card-tools">
            <a href="<?= base_url('users/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add User
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (session()->has('message')) : ?>
            <div class="alert alert-success m-3">
                <?= session('message') ?>
            </div>
        <?php endif ?>

        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger m-3">
                <?= session('error') ?>
            </div>
        <?php endif ?>

        <table class="table table-striped" id="table-users">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 150px">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= $user->username ?></td>
                        <td><?= $user->email ?></td>
                        <td>
                            <?php foreach ($user->groups as $group) : ?>
                                <span class="badge text-bg-secondary"><?= $group['name'] ?></span>
                            <?php endforeach ?>
                        </td>
                        <td>
                            <a href="<?= base_url('users/edit/' . $user->id) ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('users/delete/' . $user->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#table-users').DataTable({
            "pageLength": 10,
            "lengthMenu": [
                [10, 20, 25, 50, -1],
                [10, 20, 25, 50, "All"]
            ],
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "<?= base_url('assets/plugins/datatables/i18n/id.json') ?>"
            }
        });
    });
</script>
<?= $this->endSection() ?>
