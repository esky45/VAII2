<?php
include 'database.php';

$id = $_POST['id'];

if (isset($_POST['brightness'])) {
    $brightness = $_POST['brightness'];
    $sql = "UPDATE devices SET brightness=$brightness WHERE id=$id";
} elseif (isset($_POST['threshold'])) {
    $threshold = $_POST['threshold'];
    $sql = "UPDATE devices SET threshold=$threshold WHERE id=$id";
}

if ($conn->query($sql)) {
    header("Location: index.php");
} else {
    echo "Error: " . $conn->error;
}
?>