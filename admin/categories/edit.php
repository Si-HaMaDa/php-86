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

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // TODO: INPUT VALIDATIONS
    $name     = $_POST['name'];

    if (empty($name)) {
        $error = 'Please fill in all fields';
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE name = :name AND id NOT IN ($id)");
        $stmt->execute(['name' => $name]);
        $nameCheck = $stmt->fetch();
        if ($nameCheck) {
            $error = 'Name Already Exists';
        }
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }

    if (!$error) {
        try {
            // prepare sql and bind parameters
            $stmt = $conn->prepare("UPDATE categories SET name = :name WHERE id = :id");

            $stmt->execute([
                'id'    => $id,
                'name'  => $name,
            ]);

            $success = "Category Updated successfully!";

            $_SESSION['messages']['success'] = $success;
            echo "<script>window.location.href = '" . ADMIN_URL . "/categories';</script>";
            die;
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
    }
}

?>

<div class="page-content card">

    <?php if ($error) : ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($success) : ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form class="row g-3 card-body" method="POST">
        <div>
            <a href="<?= ADMIN_URL ?>/categories" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Edit Category</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="<?= $name ?? $category['name'] ?>" placeholder="Name...">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
