<?php
// Define the file path for the JSON file
$filePath = 'spells.json';

// Get JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (
isset($input['name'], $input['level'], $input['type'], $input['effect'], $input['description'], $input['components'])
&& is_numeric($input['level'])
&& is_numeric($input['effect'])
) {
// Sanitize the input
$spell = [
    'name' => htmlspecialchars($input['name']),
    'level' => intval($input['level']),
    'type' => htmlspecialchars($input['type']),
    'effect' => intval($input['effect']),
    'description' => htmlspecialchars($input['description']),
    'components' => htmlspecialchars($input['components']),
];

// Read existing spells from the JSON file
$spells = [];
if (file_exists($filePath)) {
    $fileContents = file_get_contents($filePath);
    $spells = json_decode($fileContents, true) ?: [];
}

// Add the new spell
$spells[] = $spell;

// Save back to the JSON file
if (file_put_contents($filePath, json_encode($spells, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save spell']);
}
<?php
// Define the file path for the JSON file
$filePath = 'spells.json';

// Check if the file exists
if (file_exists($filePath)) {
// Read and return the contents of the file
$fileContents = file_get_contents($filePath);
echo $fileContents;
} else {
// Return an empty array if the file doesn't exist
echo json_encode([]);
}
?>

} else {
echo json_encode(['success' => false, 'message' => 'Invalid input data']);
}
?>
// Function to fetch and display spells
function displaySpells() {
fetch('get_spells.php')
    .then(response => response.json())
    .then(spells => {
        const spellList = document.getElementById('spellList');
        spellList.innerHTML = '<h3>Spellbook</h3>'; // Reset list

        spells.forEach(spell => {
            const spellItem = document.createElement('div');
            spellItem.classList.add('spell-item');
            spellItem.innerHTML = `
                <h4>${spell.name} (Level ${spell.level})</h4>
                <p><strong>Type:</strong> ${spell.type}</p>
                <p><strong>Effect:</strong> ${spell.effect}</p>
                <p><strong>Description:</strong> ${spell.description}</p>
                <p><strong>Components:</strong> ${spell.components}</p>
            `;
            spellList.appendChild(spellItem);
        });
    })
    .catch(error => {
        alert('Failed to fetch spells: ' + error.message);
    });
}

// Call displaySpells on page load
window.onload = displaySpells;
