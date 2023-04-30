<?php
require_once __DIR__ . '/../../config.php';

// require __DIR__ . '/../parts/header.php';

$error = $success = '';

$name = $description = $category_id = '';
$selected_hashtags = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // TODO: INPUT VALIDATIONS
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $selected_hashtags    = $_POST['hashtags'];

    if (empty($name) || empty($description) || empty($category_id) || empty($selected_hashtags)) {
        $error = 'Please fill in all fields';
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM books WHERE name = :name");
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
            // begin the transaction
            $conn->beginTransaction();

            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO books (name, description, category_id) VALUES (:name, :description, :category_id)");

            $stmt->execute([
                'name'        => $name,
                'description' => $description,
                'category_id' => $category_id,
            ]);

            $book_id = $conn->lastInsertId();

            $book_hashtag = $conn->prepare("INSERT INTO book_hashtag (book_id, hashtag_id) VALUES (:book_id, :hashtag_id)");

            foreach ($selected_hashtags as $selected_hashtag) {
                $book_hashtag->execute([
                    'book_id' => $book_id,
                    'hashtag_id' => $selected_hashtag,
                ]);
            }

            // commit the transaction
            $conn->commit();
            $success = "Book saved successfully!";

            $_SESSION['messages']['success'] = $success;
            // echo "<script>window.location.href = '" . ADMIN_URL . "/books';</script>";
            header('location: ' . ADMIN_URL . '/books');
            die;
        } catch (PDOException $e) {
            // roll back the transaction if something failed
            $conn->rollback();

            $error = "Connection failed: " . $e->getMessage();
        }
    }
}

try {
    $categories = $conn->prepare("SELECT * FROM categories");
    $categories->execute();
    $categories = $categories->fetchAll();
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

try {
    $db_hashtags = $conn->prepare("SELECT * FROM hashtags");
    $db_hashtags->execute();
    $db_hashtags = $db_hashtags->fetchAll();
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
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
            <a href="<?= ADMIN_URL ?>/books" class="float-end btn btn-primary mt-3">Back</a>
            <h1 class="mt-3">Add Book</h1>
        </div>
        <div class="col-12">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="<?= $name ?>" placeholder="Name...">
        </div>
        <div class="col-12">
            <label for="description" class="form-label">Desc</label>
            <textarea name="description" class="form-control" id="description" cols="30" rows="10"><?= $description ?></textarea>
        </div>
        <div class="col-12">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" class="form-select" id="category_id">
                <option value="">Select Category</option>

                <?php foreach ($categories as $category) : ?>
                    <option <?= $category_id == $category['id'] ? 'selected' : '' ?> value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>

            </select>
        </div>
        <div class="col-12">
            <label for="hashtags" class="form-label">Hashtags</label>
            <select multiple name="hashtags[]" class="form-select" id="hashtags">

                <?php foreach ($db_hashtags as $hashtag) : ?>
                    <option <?= in_array($hashtag['id'], $selected_hashtags) ? 'selected' : '' ?> value="<?= $hashtag['id'] ?>"><?= $hashtag['name'] ?></option>
                <?php endforeach; ?>

            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../parts/footer.php'; ?>
