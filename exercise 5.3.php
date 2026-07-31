<?php
$host     = '127.0.0.1';
$db       = 'PHP'; 
$user     = 'root';
$password = 'Kirsten.L1404';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);

    $sql = "INSERT INTO users (Name, Email) VALUES (:name, :email)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':name'  => 'Fiqa',
        ':email' => 'Fiqa@gmail.com',
    ]);

    echo "Record inserted successfully! User ID: " . $pdo->lastInsertId();

} catch (PDOException $e) {
    exit("Database Connection Error: " . $e->getMessage());
}
?>