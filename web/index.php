<!DOCTYPE html>
<html lang="en">
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
</head>
<body>
<h1>D&D Spell Creator</h1>
<div class="form-container">
    <h2>Create a Spell</h2>
    <form id="spellForm">
        <label for="name">Spell Name:</label>
        <input type="text" id="name" placeholder="Enter spell name" required>

        <label for="level">Spell Level:</label>
        <input type="number" id="level" placeholder="Enter spell level (1-100)" required>

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
    const API_URL = 'https://your-server-endpoint/api/spells';

    async function saveSpell() {
        const spell = {
            name: document.getElementById("name").value.trim(),
            level: parseInt(document.getElementById("level").value.trim(), 10),
            type: document.getElementById("type").value.trim(),
            effect: parseInt(document.getElementById("effect").value.trim(), 10),
            description: document.getElementById("description").value.trim(),
            components: document.getElementById("components").value.trim()
        };

        if (!spell.name || isNaN(spell.level) || spell.level < 1 || spell.level > 100 || !spell.type || isNaN(spell.effect) || !spell.description || !spell.components) {
            alert("Please fill out all fields correctly. Level must be between 1 and 100.");
            return;
        }

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(spell)
            });

            if (!response.ok) {
                throw new Error('Failed to save spell');
            }

            alert("Spell saved successfully!");
            displaySpells(); // Refresh spellbook
            document.getElementById("spellForm").reset(); // Clear form
        } catch (error) {
            alert("Error saving spell: " + error.message);
        }
    }

    async function displaySpells() {
        try {
            const response = await fetch(API_URL);
            if (!response.ok) {
                throw new Error('Failed to fetch spells');
            }

            const spells = await response.json();
            const spellList = document.getElementById('spellList');
            spellList.innerHTML = '<h2>Spellbook</h2>';

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
        } catch (error) {
            alert("Error fetching spells: " + error.message);
        }
    }

    window.onload = displaySpells;
</script>
</body>
</html>
