<?php
// Database connection parameters
$host = 'ep-soft-lake-a2br8hlw.eu-central-1.pg.koyeb.app';
$port = '5432'; // Default PostgreSQL port
$dbname = 'koyebdb'; // Replace with your database name
$user = 'koyeb-adm'; // Replace with your database username
$password = 'qY2trVhBe9KS'; // Replace with your database password

// Create a connection string
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Establish a connection to the PostgreSQL database
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Prepare an SQL statement for inserting the spell data
    $stmt = $pdo->prepare('INSERT INTO spells (name, level, type, effect, description, components) VALUES (:name, :level, :type, :effect, :description, :components)');

    // Bind form data to the SQL statement parameters
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':level', $_POST['level'], PDO::PARAM_INT);
    $stmt->bindParam(':type', $_POST['type']);
    $stmt->bindParam(':effect', $_POST['effect'], PDO::PARAM_INT);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':components', $_POST['components']);

    // Execute the SQL statement
    $stmt->execute();

    // Redirect back to the main page after successful insertion
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    // Handle any errors during the database operations
    echo 'Database error: ' . $e->getMessage();
}
?>

