<?php
require __DIR__ . '/../parts/header.php';

if (!empty($_GET['delete'])) {

    $id = (int) ($_GET['delete'] ?? 0);

    try {
        $book = $conn->prepare("DELETE FROM books WHERE id = :id");

        $book->execute(['id' => $id]);

        if (!$book) {
            $_SESSION['messages']['error'] = "Unkown error!";
        } else {
            $_SESSION['messages']['success'] = "Book deleted successfully!";
        }

        echo "<script>window.location.href = '" . ADMIN_URL . "/books';</script>";
        die;
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }
}

try {
    $books = $conn->prepare("SELECT books.*, categories.name AS category_name FROM books LEFT JOIN categories ON books.category_id = categories.id");
    $books->execute();
    $books = $books->fetchAll();
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}
?>

<div class="page-content">

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div>
        <a href="<?= ADMIN_URL ?>/books/add.php" class="float-end btn btn-primary">Add Book</a>
        <h1 class="mt-3">Books</h1>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Category</td>
                <td>Description</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($books as $book) : ?>
                <tr>
                    <td><?= $book['id'] ?></td>
                    <td><?= $book['name'] ?></td>
                    <td><?= $book['category_name'] ?></td>
                    <!-- $book['description'] ?? '' -->
                    <!-- $book['description'] ? substr($book['description'], 0, 50) : '' -->
                    <td><?= substr($book['description'] ?? '', 0, 50) ?>...</td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?= ADMIN_URL ?>/books/show.php?id=<?= $book['id'] ?>">
                            Show
                        </a> |
                        <a class="btn btn-sm btn-warning" href="<?= ADMIN_URL ?>/books/edit.php?id=<?= $book['id'] ?>">
                            Edit
                        </a> |
                        <a class="btn btn-sm btn-danger" href="<?= ADMIN_URL ?>/books?delete=<?= $book['id'] ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
