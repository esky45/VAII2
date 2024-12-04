<?php
require_once 'database.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $status = $_POST['status'];
    $brightness = isset($_POST['brightness']) ? intval($_POST['brightness']) : null;
    $threshold = isset($_POST['threshold']) ? intval($_POST['threshold']) : null;

    // Server-side Validation
    if (empty($name) || strlen($name) < 3 || strlen($name) > 100 || !preg_match('/^[a-zA-Z0-9 ]+$/', $name)) {
        die('Invalid name: Must be 3-100 characters and only alphanumeric with spaces.');
    }

    if (!in_array($type, ['Lightbulb', 'Sensor', 'Other'])) {
        die('Invalid type selected.');
    }

    if (!in_array($status, ['On', 'Off'])) {
        die('Invalid status selected.');
    }

    if ($brightness !== null && (!is_numeric($brightness) || $brightness < 0 || $brightness > 100)) {
        die('Invalid brightness: Must be a number between 0 and 100.');
    }

    if ($threshold !== null && (!is_numeric($threshold) || $threshold < 0 || $threshold > 100)) {
        die('Invalid threshold: Must be a number between 0 and 100.');
    }

    // Insert data into the database using prepared statements
    $sql = "INSERT INTO devices (name, type, status, brightness, threshold) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $type, $status, $brightness, $threshold);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect to the main page after successful creation
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add New Device</title>
</head>
<body>
    <h1>Add New Smart Device</h1>
    <form action="" method="POST" id="deviceForm">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required minlength="3" maxlength="100"
               pattern="^[a-zA-Z0-9 ]+$" title="Only alphanumeric characters and spaces allowed"><br>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="Lightbulb">Lightbulb</option>
            <option value="Sensor">Sensor</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="On">On</option>
            <option value="Off">Off</option>
        </select><br>

        <label for="brightness">Brightness (if applicable):</label>
        <input type="number" name="brightness" id="brightness" min="0" max="100"><br>

        <label for="threshold">Threshold (if applicable):</label>
        <input type="number" name="threshold" id="threshold" min="0" max="100"><br>

        <button type="submit">Save Device</button>
    </form>

    <script>
        // Client-side Validation
        document.getElementById('deviceForm').addEventListener('submit', function (event) {
            const nameField = document.getElementById('name');
            const brightness = document.getElementById('brightness');
            const threshold = document.getElementById('threshold');

            if (!nameField.value.match(/^[a-zA-Z0-9 ]+$/)) {
                alert('Device name must contain only alphanumeric characters and spaces.');
                event.preventDefault(); // Stop form submission
            }

            if (brightness.value && (brightness.value < 0 || brightness.value > 100)) {
                alert('Brightness must be a number between 0 and 100.');
                event.preventDefault(); // Stop form submission
            }

            if (threshold.value && (threshold.value < 0 || threshold.value > 100)) {
                alert('Threshold must be a number between 0 and 100.');
                event.preventDefault(); // Stop form submission
            }
        });
    </script>
</body>
</html>
