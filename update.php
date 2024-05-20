<?php
require 'inc/db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare('UPDATE tblbeispiel SET name = ?, email = ? WHERE ID = ?');
    $stmt->execute([$name, $email, $id]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
}
?>
