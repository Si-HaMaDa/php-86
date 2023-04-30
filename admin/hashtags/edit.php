<?php
require __DIR__ . '/../parts/header.php';

$id = (int) ($_GET['id'] ?? 0);

try {
    $hashtag = $conn->prepare("SELECT * FROM hashtags WHERE id = :id");

    $hashtag->execute(['id' => $id]);

    $hashtag = $hashtag->fetch();

    if (!$hashtag) {
        $_SESSION['messages']['error'] = "Hashtag not found!";
        echo "<script>window.location.href = '" . ADMIN_URL . "/hashtags';</script>";
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
        $stmt = $conn->prepare("SELECT * FROM hashtags WHERE name = :name AND id NOT IN ($id)");
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
            $stmt = $conn->prepare("UPDATE hashtags SET name = :name WHERE id = :id");

            $stmt->execute([
                'id'    => $id,
                'name'  => $name,
            ]);

            $success = "Hashtag Updated successfully!";

            $_SESSION['messages']['success'] = $success;
            echo "<script>window.location.href = '" . ADMIN_URL . "/hashtags';</script>";
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
            <a href="<?= ADMIN_URL ?>/hashtags" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Edit Hashtag</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="<?= $name ?? $hashtag['name'] ?>" placeholder="Name...">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
