<?php
include("config.php");
requireAdmin();

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $stmt = $connection->prepare("DELETE FROM clients WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
}

header("Location: profile.php");
exit;
?>
