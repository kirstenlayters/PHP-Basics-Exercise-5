<?php
$host = 'localhost';
$db   = 'final_project';
$user = 'root';
$pass = 'Kirsten.L1404';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$id = '';
$name = '';
$email = '';
$message = '';
$is_editing = false;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: index.php");
    exit;
}

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
    $stmt->execute([$edit_id]);
    $record = $stmt->fetch();

    if ($record) {
        $id = $record['id'];
        $name = $record['name'];
        $email = $record['email'];
        $message = $record['message'];
        $is_editing = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $id = $_POST['id'] ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        if (!empty($id)) {
            $stmt = $pdo->prepare("UPDATE messages SET name = ?, email = ?, message = ? WHERE id = ?");
            $stmt->execute([$name, $email, $message, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
        }
        header("Location: index.php");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM messages ORDER BY id DESC");
$records = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Project - CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { margin-bottom: 30px; max-width: 400px; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; margin: 6px 0 12px; box-sizing: border-box; }
        table { border-collapse: collapse; width: 100%; max-width: 800px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 6px 12px; background: #28a745; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-cancel { background: #6c757d; }
        .action-link { text-decoration: none; margin-right: 10px; }
        .delete-link { color: red; }
    </style>
</head>
<body>

    <h2><?= $is_editing ? 'Edit Entry' : 'Submit User Data' ?></h2>

    <form action="index.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required><?= htmlspecialchars($message) ?></textarea>

        <button type="submit" class="btn"><?= $is_editing ? 'Update' : 'Save' ?></button>
        <?php if ($is_editing): ?>
            <a href="index.php" class="btn btn-cancel">Cancel</a>
        <?php endif; ?>
    </form>

    <hr>

    <h2>Submitted Records</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($records) > 0): ?>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td>
                            <a href="index.php?edit=<?= $row['id'] ?>" class="action-link">Edit</a>
                            <a href="index.php?delete=<?= $row['id'] ?>" class="action-link delete-link" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No records found.</td>
                </tr>
            <?php if ($is_editing): ?>
            <?php endif; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
