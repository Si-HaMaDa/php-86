<?php
require_once __DIR__ . '/../../config.php';

// require __DIR__ . '/../parts/header.php';

$error = $success = '';

$name = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // TODO: INPUT VALIDATIONS
    $name     = $_POST['name'];

    if (empty($name)) {
        $error = 'Please fill in all fields';
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM hashtags WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $hashtag = $stmt->fetch();
        if ($hashtag) {
            $error = 'Name Already Exists';
        }
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }

    if (!$error) {
        try {
            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO hashtags (name) VALUES (:name)");

            $stmt->execute(['name' => $name]);

            $success = "Hashtag saved successfully!";

            $_SESSION['messages']['success'] = $success;
            // echo "<script>window.location.href = '" . ADMIN_URL . "/hashtags';</script>";
            header('location: ' . ADMIN_URL . '/hashtags');
            die;
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
    }
}

require __DIR__ . '/../parts/header.php';

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
        <div class="card-title">
            <a href="<?= ADMIN_URL ?>/hashtags" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Add Hashtag</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="<?= $name ?>" placeholder="Name...">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
