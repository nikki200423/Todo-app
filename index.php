<?php
include "db.php";

// Handle filters
$status_filter = $_GET['status'] ?? 'all';

$sql = "SELECT * FROM todos";
if ($status_filter === 'done') {
    $sql .= " WHERE checked = 1";
} elseif ($status_filter === 'notdone') {
    $sql .= " WHERE checked = 0";
}
$sql .= " ORDER BY due_date ASC, priority DESC, id DESC";

$all_tasks = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced To-Do List</title>
    <link rel="stylesheet" href="style.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #clock {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 My To-Do List</h2>

        <!-- Live Clock -->
        <div id="clock">Loading time...</div>

        <!-- Task Entry Form -->
        <form action="insert.php" method="POST" class="task-form">
            <input type="text" name="task" placeholder="Task" required>
            <input type="date" name="due_date" required>
            <select name="priority">
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
            </select>
            <input type="text" name="category" placeholder="Category (e.g. Work)">
            <button type="submit">Add</button>
        </form>

        <!-- Filters -->
        <div class="filters">
            <a href="?status=all" class="<?= $status_filter === 'all' ? 'active' : '' ?>">All</a>
            <a href="?status=done" class="<?= $status_filter === 'done' ? 'active' : '' ?>">Done</a>
            <a href="?status=notdone" class="<?= $status_filter === 'notdone' ? 'active' : '' ?>">Not Done</a>
        </div>

        <!-- Task List -->
        <ul>
            <?php while ($row = $all_tasks->fetch_assoc()) { ?>
                <li>
                    <div class="task-text <?= $row['checked'] ? 'done' : '' ?>">
                        <strong><?= htmlspecialchars($row['task']) ?></strong><br>
                        <small>📅 <?= $row['due_date'] ?> | 🏷 <?= $row['category'] ?> | 🔥 <?= $row['priority'] ?></small>
                    </div>
                    <div class="actions">
                        <a href="update.php?id=<?= $row['id'] ?>">
                            <?= $row['checked'] ? '↩️' : '✅' ?>
                        </a>
                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this task?')">🗑️</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>

    <!-- ✅ Task Completed Popup -->
    <?php if (isset($_GET['completed'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Task Completed!',
            text: 'Well done ✅',
            timer: 1500,
            showConfirmButton: false
        });
    </script>
    <?php endif; ?>

    <!-- ⏰ Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString();
            const date = now.toLocaleDateString();
            document.getElementById("clock").innerText = `📅 ${date} | 🕒 ${time}`;
        }

        setInterval(updateClock, 1000);
        updateClock(); // Run immediately
    </script>
</body>
</html>
