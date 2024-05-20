<?php
require 'inc/db-connect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM tblbeispiel WHERE ID = ?");
    $stmt->execute([$id]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    echo "Fehler: ID nicht festgelegt.";
}
?>
