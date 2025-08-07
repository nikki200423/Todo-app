<?php
include "db.php";

if (isset($_POST['task'])) {
    $task = trim($_POST['task']);
    $due_date = $_POST['due_date'] ?? null;
    $priority = $_POST['priority'] ?? 'Medium';
    $category = $_POST['category'] ?? 'General';

    if (!empty($task)) {
        $sql = "INSERT INTO todos (task, due_date, priority, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $task, $due_date, $priority, $category);
        $stmt->execute();
    }
}

header("Location: index.php");
exit();
