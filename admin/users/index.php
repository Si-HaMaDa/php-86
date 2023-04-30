<?php
require __DIR__ . '/../parts/header.php';

if (!empty($_GET['delete'])) {

    $id = (int) ($_GET['delete'] ?? 0);

    try {
        $user = $conn->prepare("DELETE FROM users WHERE id = :id");

        $user->execute(['id' => $id]);

        if (!$user) {
            $_SESSION['messages']['error'] = "Unkown error!";
        } else {
            $_SESSION['messages']['success'] = "User deleted successfully!";
        }

        echo "<script>window.location.href = '" . ADMIN_URL . "/users';</script>";
        die;
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }
}

try {
    $users = $conn->prepare("SELECT * FROM users");
    $users->execute();
    $users = $users->fetchAll();
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}
?>

<div class="page-content">
    <div>
        <a href="<?=ADMIN_URL?>/users/add.php" class="float-end btn btn-primary">Add User</a>
        <h1 class="mt-3">Users</h1>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Email</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?=$user['id']?></td>
                    <td><?=$user['name']?></td>
                    <td><?=$user['email']?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?=ADMIN_URL?>/users/show.php?id=<?=$user['id']?>">
                            Show
                        </a> |
                        <a class="btn btn-sm btn-warning" href="<?=ADMIN_URL?>/users/edit.php?id=<?=$user['id']?>">
                            Edit
                        </a> |
                        <a class="btn btn-sm btn-danger" href="<?=ADMIN_URL?>/users?delete=<?=$user['id']?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach;?>

        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../parts/footer.php';?>
