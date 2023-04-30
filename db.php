<?php
$db_servername = "127.0.0.1";
$db_username   = "valet";
$db_password   = "root";
$db_name       = "php_86";

/* // Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
 */

try {
    // PDO connection
    $conn = new PDO("mysql:host=$db_servername;dbname=$db_name;charset=utf8mb4", $db_username, $db_password);
    // $conn = new PDO("sqlite:" . __DIR__ . "/database.sql");

    // create BD query
    // $conn->query("CREATE DATABASE php_86");

    // create table
    // $conn->query("CREATE TABLE users (
    //         id int AUTO_INCREMENT,
    //         name varchar(100) NOT NULL,
    //         email varchar(255) NOT NULL,
    //         password varchar(255),
    //         PRIMARY KEY (id),
    //         UNIQUE (email)
    //     );");

    // insert data
    // $sql = "INSERT INTO users (name, email, password) VALUES ('John', '" . time() . "@example.com', '123456')";
    // $conn->exec($sql);

    // Get last inserted ID
    // $last_id = $conn->lastInsertId();
    // echo "New record created successfully. Last inserted ID is: " . $last_id;

    // select database
    // $data = $conn->query("SELECT * FROM users");
    // $data = $conn->query("SELECT * FROM users WHERE email = 'john@example.com' AND password = '123'");

    // $data = $data->fetchAll(PDO::FETCH_ASSOC);

    // echo "<pre>";
    // // print_r($data[0]['email']);
    // print_r($data);

    // Delete user information
    // $conn->query("DELETE FROM users WHERE id = '1'");

    // Update user information
    // $conn->query("UPDATE users SET name = 'Jhon', email = 'john@example.com' WHERE id = '3'");]

    // prepare sql and bind parameters
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

// insert a row
    $name     = "John";
    $email    = "john1@example.com";
    $password = "Doe";
    $stmt->execute();

// insert another row
    $name     = "Mary";
    $email    = "mary@example.com";
    $password = "Moe";
    $stmt->execute();

// insert another row
    $name     = "Julie";
    $email    = "julie@example.com";
    $password = "Dooley";
    $stmt->execute();

    echo "New records created successfully";

    // close connection
    $conn = null;

    echo "Connected successfully";
} catch (PDOException $e) {
    echo "From Catch:<br>";
    echo "Connection failed: " . $e->getMessage();
}

echo "<br> END OF FILE";
