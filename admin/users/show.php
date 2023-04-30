<?php
require __DIR__ . '/../parts/header.php';

$id = (int) ($_GET['id'] ?? 0);

try {
    $user = $conn->prepare("SELECT * FROM users WHERE id = :id");

    $user->execute(['id' => $id]);

    $user = $user->fetch();

    if (!$user) {
        $_SESSION['messages']['error'] = "User not found!";
        echo "<script>window.location.href = '" . ADMIN_URL . "/users';</script>";
        die;
    }

} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

?>

<div class="page-content card">

    <div class="row g-3 card-body">
        <div>
            <a href="<?=ADMIN_URL?>/users" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Show User</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" disabled id="inputName" name="name" value="<?=$user['name']?>" placeholder="Name...">
        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Email</label>
            <input type="email" class="form-control" disabled id="inputEmail4" name="email" value="<?=$user['email']?>" placeholder="eamil@example.com">
        </div>
        <div class="col-md-6">
            <label for="inputPassword4" class="form-label">Password</label>
            <input type="password" class="form-control" disabled id="inputPassword4" name="password">
        </div>
    </div>
</div>

<?php require __DIR__ . '/../parts/footer.php';?>
