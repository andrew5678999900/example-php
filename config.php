<?php
return [
    'db_host' => 'ep-soft-lake-a2br8hlw.eu-central-1.pg.koyeb.app',
    'db_port' => '5432', // Default PostgreSQL port
    'db_name' => 'koyebdb',
    'db_user' => 'koyeb-adm',
    'db_pass' => 'qY2trVhBe9KS',
    CREATE TABLE spells (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    level INT NOT NULL,
    type VARCHAR(255) NOT NULL,
    effect INT NOT NULL,
    description TEXT NOT NULL,
    components VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

];
<?php
$config = include('config.php');

try {
    $dsn = "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};";
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the PostgreSQL database successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
