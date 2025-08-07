<?php
include "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get current checked status
    $sql = "SELECT checked FROM todos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($checked);
    $stmt->fetch();
    $stmt->close();

    // Toggle checked
    $new_status = $checked ? 0 : 1;

    $update = "UPDATE todos SET checked=? WHERE id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();

    // Redirect with status flag
    $redirect = "Location: index.php";
    if ($new_status === 1) {
        $redirect .= "?status=all&completed=1";
    }
    header($redirect);
    exit();
}
