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

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // TODO: INPUT VALIDATIONS
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email)) {
        $error = 'Please fill in all fields';
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND id NOT IN ($id)");
        $stmt->execute(['email' => $email]);
        $emailCheck = $stmt->fetch();
        if ($emailCheck) {
            $error = 'Email Already Exists';
        }
    } catch (PDOException $e) {
        $error = "Connection failed: " . $e->getMessage();
    }

    if (!$error) {
        try {

            $passwordQuery = !empty($password) ? ", password = :password" : '';

            $sql = "UPDATE users SET name = :name, email = :email $passwordQuery WHERE id = :id";

            // prepare sql and bind parameters
            $stmt = $conn->prepare($sql);

            $data = [
                'id'    => $id,
                'name'  => $name,
                'email' => $email,
            ];

            if (!empty($password)) {
                $data['password'] = $password;
            }

            $stmt->execute($data);

            $success = "User Updated successfully!";

            $_SESSION['messages']['success'] = $success;
            echo "<script>window.location.href = '" . ADMIN_URL . "/users';</script>";
            die;
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
    }

}

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
        <div>
            <a href="<?=ADMIN_URL?>/users" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Edit User</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="<?=$name ?? $user['name']?>" placeholder="Name...">
        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail4" name="email" value="<?=$email ?? $user['email']?>" placeholder="eamil@example.com">
        </div>
        <div class="col-md-6">
            <label for="inputPassword4" class="form-label">Password</label>
            <input type="password" class="form-control" id="inputPassword4" name="password">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../parts/footer.php';?>
