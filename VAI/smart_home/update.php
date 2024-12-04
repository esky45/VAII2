<?php
require_once 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM devices WHERE id = $id");
    $device = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $status = $_POST['status'];
    $brightness = isset($_POST['brightness']) ? intval($_POST['brightness']) : null;
    $threshold = isset($_POST['threshold']) ? intval($_POST['threshold']) : null;

    $sql = "UPDATE devices SET name = ?, type = ?, status = ?, brightness = ?, threshold = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiii", $name, $type, $status, $brightness, $threshold, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
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
    <title>Edit Device</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Device</h1>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $device['id']; ?>">

            <!-- Device Name -->
            <label for="name">Device Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $device['name']; ?>" required minlength="3" maxlength="100">

            <!-- Device Type -->
            <label for="type">Device Type:</label>
            <select name="type" id="type" required>
                <option value="Lightbulb" <?php echo $device['type'] === 'Lightbulb' ? 'selected' : ''; ?>>Lightbulb</option>
                <option value="Sensor" <?php echo $device['type'] === 'Sensor' ? 'selected' : ''; ?>>Sensor</option>
                <option value="Other" <?php echo $device['type'] === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <!-- Device Status -->
            <label for="status">Device Status:</label>
            <div class="status-container">
                <select name="status" id="status" required>
                    <option value="On" <?php echo $device['status'] === 'On' ? 'selected' : ''; ?>>On</option>
                    <option value="Off" <?php echo $device['status'] === 'Off' ? 'selected' : ''; ?>>Off</option>
                </select>

                <!-- Display status indicator -->
                <div class="status-indicator">
                    <span class="status-light <?php echo strtolower($device['status']) === 'on' ? 'on' : 'off'; ?>"></span>
                    <?php echo $device['status']; ?>
                </div>
            </div>

            <!-- Brightness -->
            <label for="brightness">Brightness (0-100):</label>
            <input type="number" name="brightness" id="brightness" value="<?php echo $device['brightness']; ?>" min="0" max="100">

            <!-- Threshold -->
            <label for="threshold">Threshold (0-100):</label>
            <input type="number" name="threshold" id="threshold" value="<?php echo $device['threshold']; ?>" min="0" max="100">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
