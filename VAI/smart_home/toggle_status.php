<?php
require_once 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Devices Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <header class="text-center mb-20">
            <h1>Smart Devices Dashboard</h1>
            <p>Manage your smart home devices seamlessly.</p>

            <a href="create.php" class="button mt-20">+ Add New Device</a>
            <br/>
            <a href="map.php" class="button mt-20">Device Map</a>
        </header>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Brightness</th>
                        <th>Threshold</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM devices");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $isLightBulb = strtolower($row['type']) === 'lightbulb';
                            $isSensor = strtolower($row['type']) === 'sensor';
                            $status = strtolower($row['status']);

                            echo "<tr>">
                                . "<td>{$row['name']}</td>"
                                . "<td>{$row['type']}</td>"
                                . "<td>"
                                . "<a href='#' class='toggle-status' data-id='{$row['id']}' data-status='{$row['status']}'>"
                                . "<span class='status-light " . ($status === 'on' ? 'on' : 'off') . "'></span>{$row['status']}</a>"
                                . "</td>";

                            if ($isLightBulb) {
                                echo "<td>"
                                    . "<div class='slider-container'>"
                                    . "<input type='range' id='brightness-{$row['id']}' class='brightness-slider' min='0' max='100' value='{$row['brightness']}'>"
                                    . "<span id='brightness-value-{$row['id']}'>{$row['brightness']}%</span>"
                                    . "</div>"
                                    . "</td>";
                            } else {
                                echo "<td>N/A</td>";
                            }

                            if ($isSensor) {
                                echo "<td>"
                                    . "<div class='slider-container'>"
                                    . "<input type='range' id='threshold-{$row['id']}' class='threshold-slider' min='0' max='100' value='{$row['threshold']}'>"
                                    . "<span id='threshold-value-{$row['id']}'>{$row['threshold']}%</span>"
                                    . "</div>"
                                    . "</td>";
                            } else {
                                echo "<td>N/A</td>";
                            }

                            echo "<td>"
                                . "<a href='update.php?id={$row['id']}' class='button'>Edit</a>"
                                . "<a href='delete.php?id={$row['id']}' class='button danger'>Delete</a>"
                                . "</td>"
                                . "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No devices found. Add a new device to get started.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript to handle dynamic interactions -->
    <script>
        // Handle status toggle
        $('.toggle-status').click(function (e) {
            e.preventDefault();

            const link = $(this);
            const deviceId = link.data('id');
            const currentStatus = link.data('status');
            const newStatus = currentStatus.toLowerCase() === 'on' ? 'Off' : 'On';

            $.ajax({
                url: 'toggle_status.php',
                type: 'POST',
                data: { id: deviceId, status: newStatus },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        link.text(newStatus);
                        link.data('status', newStatus);
                        link.find('.status-light').removeClass('on off').addClass(newStatus.toLowerCase());
                    } else {
                        alert('Error: ' + (res.error || 'Failed to update status.'));
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        });

        // Update the brightness value dynamically
        document.querySelectorAll('.brightness-slider').forEach(function (slider) {
            slider.addEventListener('input', function () {
                const sliderId = this.id.split('-')[1];
                document.getElementById('brightness-value-' + sliderId).textContent = this.value + '%';
            });
        });

        // Update the threshold value dynamically
        document.querySelectorAll('.threshold-slider').forEach(function (slider) {
            slider.addEventListener('input', function () {
                const sliderId = this.id.split('-')[1];
                document.getElementById('threshold-value-' + sliderId).textContent = this.value + '%';
            });
        });
    </script>
</body>
</html>
