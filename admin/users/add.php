<?php
require_once __DIR__ . '/../../config.php';

// require __DIR__ . '/../parts/header.php';

$error = $success = '';

$name = $email = $password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // TODO: INPUT VALIDATIONS
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user) {
            $error = 'Email Already Exists';
        }
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }

    if (!$error) {
        try {
            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
            ]);

            $success = "User saved successfully!";

            $_SESSION['messages']['success'] = $success;
            // echo "<script>window.location.href = '" . ADMIN_URL . "/users';</script>";
            header('location: ' . ADMIN_URL . '/users');
            die;
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
    }

}

require __DIR__ . '/../parts/header.php';

?>

<div class="page-content card">


    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?=$error?>
        </div>
    <?php endif;?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?=$success?>
        </div>
    <?php endif;?>

    <form class="row g-3 card-body" method="POST">
        <div class="card-title">
            <a href="<?=ADMIN_URL?>/users" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Add User</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="<?=$name?>" placeholder="Name...">
        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail4" name="email" value="<?=$email?>" placeholder="eamil@example.com">
        </div>
        <div class="col-md-6">
            <label for="inputPassword4" class="form-label">Password</label>
            <input type="password" class="form-control" id="inputPassword4" name="password">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../parts/footer.php';?>
