<?php
require __DIR__ . '/../parts/header.php';

$id = (int) ($_GET['id'] ?? 0);

try {
    $category = $conn->prepare("SELECT * FROM categories WHERE id = :id");

    $category->execute(['id' => $id]);

    $category = $category->fetch();

    if (!$category) {
        $_SESSION['messages']['error'] = "Category not found!";
        echo "<script>window.location.href = '" . ADMIN_URL . "/categories';</script>";
        die;
    }
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

?>

<div class="page-content card">

    <div class="row g-3 card-body">
        <div>
            <a href="<?= ADMIN_URL ?>/categories" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Show Category</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" disabled id="inputName" name="name" value="<?= $category['name'] ?>" placeholder="Name...">
        </div>
    </div>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
