<?php

require __DIR__ . '/config.php';

$error = $success = '';

// var_dump($_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] == 'POST'):

    // var_dump("POSTED");
    // var_dump($_POST);

    // TODO: INPUT VALIDATIONS
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    }

    try {
        // $sql  = "SELECT * FROM users WHERE email = '$email'";
        // $user = $conn->query($sql);

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
            // $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            /*
            123456');delete from users where id = 3;
            SQL injection
             */
            // var_dump($sql);
            // die;
            // $conn->query($sql);

            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            // $stmt->bindParam(':name', $name);
            // $stmt->bindParam(':email', $email);
            // $stmt->bindValue(':password', $password);

            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
            ]);

            $success = "You're registered successfully!";
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
    }

endif;

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>SignUp Template · Bootstrap v5.2</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">

    <link href="https://getbootstrap.com/docs/5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#712cf9">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/5.2/examples/sign-in/signin.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin w-100 m-auto">
        <form method="POST">
            <img class="mb-4" src="https://getbootstrap.com/docs/5.2/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Please sign Up</h1>

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

            <div class="form-floating">
                <input type="text" class="form-control" id="name" placeholder="Name..." name="name">
                <label for="name">Name</label>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                <label for="floatingInput">Email address</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                <label for="floatingPassword">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign Up</button>
            <p>
                Already have account, <a href="login.php">Login...</a>
            </p>
            <p class="mt-5 mb-3 text-muted">&copy; 2017–2022</p>
        </form>
    </main>

</body>

</html>
