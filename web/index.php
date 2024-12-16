<?php
$filePath = 'spells.json';

// Handle saving spells
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['name'], $input['level'], $input['type'], $input['effect'], $input['description'], $input['components'])) {
        $spell = [
            'name' => htmlspecialchars($input['name']),
            'level' => intval($input['level']),
            'type' => htmlspecialchars($input['type']),
            'effect' => intval($input['effect']),
            'description' => htmlspecialchars($input['description']),
            'components' => htmlspecialchars($input['components']),
        ];

        // Load existing spells
        $spells = [];
        if (file_exists($filePath)) {
            $spells = json_decode(file_get_contents($filePath), true) ?: [];
        }

        $spells[] = $spell;

        // Save spells to file
        if (file_put_contents($filePath, json_encode($spells, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save spell']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
    exit;
}

// Handle fetching spells
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Content-Type: application/json");
    if (file_exists($filePath)) {
        echo file_get_contents($filePath);
    } else {
        echo json_encode([]);
    }
    exit;
}
?>


<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>D&D Spell Creator</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 0;
    }

    .form-container, .spellbook {
        margin-bottom: 20px;
    }

    .spell-item {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    button {
        padding: 8px 12px;
        margin: 5px 0;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>

<h1>D&D Spell Creator</h1>
<div class="form-container">
    <h2>Create a Spell</h2>
    <form id="spellForm">
        <label for="name">Spell Name:</label>
        <input type="text" id="name" placeholder="Enter spell name" required>

        <label for="level">Spell Level:</label>
        <input type="number" id="level" placeholder="Enter spell level (1-9)" required>

        <label for="type">Spell Type:</label>
        <input type="text" id="type" placeholder="Enter spell type (e.g., Fire, Ice)" required>

        <label for="effect">Effect Power:</label>
        <input type="number" id="effect" placeholder="Enter effect power" required>

        <label for="description">Spell Description:</label>
        <textarea id="description" placeholder="Enter spell description" rows="4" required></textarea>

        <label for="components">Spell Components:</label>
        <input type="text" id="components" placeholder="Enter spell components" required>

        <button type="button" onclick="saveSpell()">Save Spell</button>
    </form>
</div>

<div class="spellbook" id="spellList">
    <h2>Spellbook</h2>
</div>

<script>
    // Save spell to the server
    function saveSpell() {
        const spell = {
            name: document.getElementById("name").value.trim(),
            level: parseInt(document.getElementById("level").value.trim(), 10),
            type: document.getElementById("type").value.trim(),
            effect: parseInt(document.getElementById("effect").value.trim(), 10),
            description: document.getElementById("description").value.trim(),
            components: document.getElementById("components").value.trim()
        };

        // Validate inputs
        if (!spell.name || isNaN(spell.level) || spell.level < 1 || spell.level > 9 || !spell.type || isNaN(spell.effect) || !spell.description || !spell.components) {
            alert("Please fill out all fields correctly. Level must be between 1 and 9.");
            return;
        }

        fetch(window.location.href, { // Same file handles saving
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(spell)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Spell saved successfully!");
                displaySpells(); // Refresh spellbook
                document.getElementById("spellForm").reset(); // Clear form
            } else {
                alert("Failed to save spell: " + data.message);
            }
        })
        .catch(error => alert("Error: " + error.message));
    }

    // Fetch and display spells
    function displaySpells() {
        fetch(window.location.href)
            .then(response => response.json())
            .then(spells => {
                if (!Array.isArray(spells)) {
                    alert("Invalid data received.");
                    return;
                }

                const spellList = document.getElementById('spellList');
                spellList.innerHTML = '<h2>Spellbook</h2>'; // Reset list

                spells.forEach(spell => {
                    const spellItem = document.createElement('div');
                    spellItem.classList.add('spell-item');
                    spellItem.innerHTML = `
                        <h3>${spell.name} (Level ${spell.level})</h3>
                        <p><strong>Type:</strong> ${spell.type}</p>
                        <p><strong>Effect:</strong> ${spell.effect}</p>
                        <p><strong>Description:</strong> ${spell.description}</p>
                        <p><strong>Components:</strong> ${spell.components}</p>
                    `;
                    spellList.appendChild(spellItem);
                });
            })
            .catch(error => alert('Failed to fetch spells: ' + error.message));
    }

    // Load spells on page load
    window.onload = displaySpells;
</script>

