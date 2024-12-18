<form id="spellForm" method="POST" action="save_spell.php">
<?php
// Database connection parameters
$host = 'ep-soft-lake-a2br8hlw.eu-central-1.pg.koyeb.app';
$port = '5432';
$dbname = 'koyebdb';
$user = 'koyeb-adm';
$password = 'qY2trVhBe9KS';

// Create a connection string
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Establish a connection to the PostgreSQL database
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Fetch all spells from the database
    $stmt = $pdo->query('SELECT name, level, type, effect, description, components FROM spells ORDER BY name');
    $spells = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle any errors during the database operations
    echo 'Database error: ' . $e->getMessage();
    $spells = [];
}
?>
<div class="spellbook" id="spellList">
    <h2>Spellbook</h2>
    <?php foreach ($spells as $spell): ?>
        <div class="spell-item">
            <h3><?= htmlspecialchars($spell['name']) ?> (Level <?= htmlspecialchars($spell['level']) ?>)</h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($spell['type']) ?></p>
            <p><strong>Effect:</strong> <?= htmlspecialchars($spell['effect']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($spell['description']) ?></p>
            <p><strong>Components:</strong> <?= htmlspecialchars($spell['components']) ?></p>
        </div>
    <?php endforeach; ?>
</div>
