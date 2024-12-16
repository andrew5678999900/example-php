CREATE DATABASE dnd_spellbook;

USE dnd_spellbook;

CREATE TABLE spells (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    level INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    effect INT NOT NULL,
    description TEXT NOT NULL,
    components VARCHAR(255) NOT NULL
);
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Spellbook</title>
    <style>
        /* CSS (same as before) */
    </style>
</head>
<body>
    <div class="container">
        <h1>D&D Spellbook</h1>

        <!-- Spell Form -->
        <div>
            <h3>Create a Spell</h3>

            <div class="form-group">
                <label for="spellName">Spell Name:</label>
                <input type="text" id="spellName" placeholder="Enter spell name">
            </div>

            <div class="form-group">
                <label for="spellLevel">Spell Level:</label>
                <input type="number" id="spellLevel" min="0" placeholder="Enter spell level (e.g., 0 for cantrip)">
            </div>

            <div class="form-group">
                <label for="spellType">Spell Type:</label>
                <select id="spellType">
                    <option value="damage">Damage</option>
                    <option value="healing">Healing</option>
                    <option value="buff">Buff</option>
                </select>
            </div>

            <div class="form-group">
                <label for="spellEffect">Effect Value:</label>
                <input type="number" id="spellEffect" placeholder="Enter effect value (e.g., 10)">
            </div>

            <div class="form-group">
                <label for="spellDescription">Spell Description:</label>
                <textarea id="spellDescription" rows="4" placeholder="Enter a detailed description of the spell"></textarea>
            </div>

            <div class="form-group">
                <label for="spellComponents">Spell Components:</label>
                <input type="text" id="spellComponents" placeholder="Enter components (e.g., verbal, somatic, material)">
            </div>

            <button onclick="createSpell()">Add Spell</button>
        </div>

        <!-- Spell List -->
        <div class="spell-list" id="spellList">
            <h3>Spellbook</h3>
        </div>
    </div>
      <script>
        // Function to create a spell and send to the server
        function createSpell() {
            const name = document.getElementById('spellName').value;
            const level = parseInt(document.getElementById('spellLevel').value);
            const type = document.getElementById('spellType').value;
            const effect = parseInt(document.getElementById('spellEffect').value);
            const description = document.getElementById('spellDescription').value;
            const components = document.getElementById('spellComponents').value;

            if (!name || isNaN(level) || isNaN(effect) || effect <= 0 || !description || !components) {
                alert('Please fill in all fields with valid values.');
                return;
            }

            // Spell data to send
            const spellData = {
                name,
                level,
                type,
                effect,
                description,
                components,
            };

            // Send data to the server
            fetch('save_spell.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(spellData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Spell successfully saved!');
                    displaySpells();
                } else {
                    alert('Failed to save the spell: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error communicating with server: ' + error.message);
            });

            clearForm();
        }

        // Function to clear form inputs
        function clearForm() {
            document.getElementById('spellName').value = '';
            document.getElementById('spellLevel').value = '';
            document.getElementById('spellType').value = 'damage';
            document.getElementById('spellEffect').value = '';
            document.getElementById('spellDescription').value = '';
            document.getElementById('spellComponents').value = '';
        }
    </script>
</body>
</html>
<?php
// Database connection
$host = 'localhost';
$dbname = 'dnd_spellbook';
$user = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Get JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (
    isset($input['name'], $input['level'], $input['type'], $input['effect'], $input['description'], $input['components'])
    && is_numeric($input['level'])
    && is_numeric($input['effect'])
) {
    $name = $input['name'];
    $level = $input['level'];
    $type = $input['type'];
    $effect = $input['effect'];
    $description = $input['description'];
    $components = $input['components'];

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO spells (name, level, type, effect, description, components) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $level, $type, $effect, $description, $components]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to save spell: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
}
?>
