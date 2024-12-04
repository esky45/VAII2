<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM devices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }
} elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Device</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container text-center">
        <h1>Delete Device</h1>
        <p>Are you sure you want to delete this device?</p>
        <form action="delete.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="button danger">Yes, Delete</button>
            <a href="index.php" class="button">Cancel</a>
        </form>
    </div>
</body>
</html>