<?php
$host = 'localhost';
$db   = 'PHP';
$user = 'root';
$pass = 'Kirsten.L1404';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$name  = 'Alex';
$email = 'alex@gmail.com';

$sql = "INSERT INTO users (Name, Email) VALUES (:name, :email)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'name'  => $name,
    'email' => $email
]);

echo "Created user with ID: " . $pdo->lastInsertId() . "<br>";

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();

echo "<h3>User List:</h3>";
foreach ($users as $u) {
    echo "ID: " . $u['User_ID'] . " | Name: " . $u['Name'] . " | Email: " . $u['Email'] . "<br>";
}

$newEmail = 'vee.updated@gmail.com';
$userIdToUpdate = 10;

$sql = "UPDATE users SET Email = :email WHERE User_ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'email' => $newEmail,
    'id'    => $userIdToUpdate
]);

echo "<br>Updated Email for User_ID " . $userIdToUpdate . "<br>";

$userIdToDelete = 11;

$sql = "DELETE FROM users WHERE User_ID = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id' => $userIdToDelete
]);

echo "Deleted User_ID " . $userIdToDelete . "<br>";
?>
