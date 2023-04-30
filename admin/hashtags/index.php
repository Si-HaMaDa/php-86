<?php
require __DIR__ . '/../parts/header.php';

if (!empty($_GET['delete'])) {

    $id = (int) ($_GET['delete'] ?? 0);

    try {
        $user = $conn->prepare("DELETE FROM hashtags WHERE id = :id");

        $user->execute(['id' => $id]);

        if (!$user) {
            $_SESSION['messages']['error'] = "Unkown error!";
        } else {
            $_SESSION['messages']['success'] = "Hashtag deleted successfully!";
        }

        echo "<script>window.location.href = '" . ADMIN_URL . "/hashtags';</script>";
        die;
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }
}

try {
    $hashtags = $conn->prepare("SELECT * FROM hashtags");
    $hashtags->execute();
    $hashtags = $hashtags->fetchAll();
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}
?>

<div class="page-content">
    <div>
        <a href="<?= ADMIN_URL ?>/hashtags/add.php" class="float-end btn btn-primary">Add Hashtag</a>
        <h1 class="mt-3">Hashtags</h1>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($hashtags as $hashtag) : ?>
                <tr>
                    <td><?= $hashtag['id'] ?></td>
                    <td><?= $hashtag['name'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?= ADMIN_URL ?>/hashtags/show.php?id=<?= $hashtag['id'] ?>">
                            Show
                        </a> |
                        <a class="btn btn-sm btn-warning" href="<?= ADMIN_URL ?>/hashtags/edit.php?id=<?= $hashtag['id'] ?>">
                            Edit
                        </a> |
                        <a class="btn btn-sm btn-danger" href="<?= ADMIN_URL ?>/hashtags?delete=<?= $hashtag['id'] ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
