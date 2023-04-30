<?php
require __DIR__ . '/../parts/header.php';

if (!empty($_GET['delete'])) {

    $id = (int) ($_GET['delete'] ?? 0);

    try {
        $user = $conn->prepare("DELETE FROM categories WHERE id = :id");

        $user->execute(['id' => $id]);

        if (!$user) {
            $_SESSION['messages']['error'] = "Unkown error!";
        } else {
            $_SESSION['messages']['success'] = "Category deleted successfully!";
        }

        echo "<script>window.location.href = '" . ADMIN_URL . "/categories';</script>";
        die;
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }
}

try {
    $categories = $conn->prepare("SELECT * FROM categories");
    $categories->execute();
    $categories = $categories->fetchAll();
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}
?>

<div class="page-content">
    <div>
        <a href="<?= ADMIN_URL ?>/categories/add.php" class="float-end btn btn-primary">Add Category</a>
        <h1 class="mt-3">Categories</h1>
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

            <?php foreach ($categories as $category) : ?>
                <tr>
                    <td><?= $category['id'] ?></td>
                    <td><?= $category['name'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?= ADMIN_URL ?>/categories/show.php?id=<?= $category['id'] ?>">
                            Show
                        </a> |
                        <a class="btn btn-sm btn-warning" href="<?= ADMIN_URL ?>/categories/edit.php?id=<?= $category['id'] ?>">
                            Edit
                        </a> |
                        <a class="btn btn-sm btn-danger" href="<?= ADMIN_URL ?>/categories?delete=<?= $category['id'] ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
