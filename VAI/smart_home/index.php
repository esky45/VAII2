<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Devices Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="text-center mb-20">
            <h1>Smart Devices Dashboard</h1>
            <p>Manage your smart home devices seamlessly.</p>
            
            <a href="create.php" class="button mt-20">+ Add New Device</a>
            <br/>
            <a href="map.php" class="button mt-20"> Device Map</a>
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
                    require_once 'database.php';

                    $result = $conn->query("SELECT * FROM devices");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Determine if the device is a Light Bulb or Sensor
                            $isLightBulb = strtolower($row['type']) === 'lightbulb';
                            $isSensor = strtolower($row['type']) === 'sensor';

                            // Start generating the HTML row
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['type']}</td>
                                    <td>
                                        <div class='status-indicator'>
                                            <span class='status-light " . (strtolower($row['status']) === 'on' ? 'on' : 'off') . "'></span>
                                            {$row['status']}
                                        </div>
                                    </td>";

                            // Show Brightness Slider if it's a Light Bulb
                            if ($isLightBulb) {
                                echo "<td>
                                        <div class='slider-container'>
                                            <input type='range' id='brightness-{$row['id']}' class='brightness-slider' name='brightness' min='0' max='100' value='{$row['brightness']}' step='1'>
                                            <span id='brightness-value-{$row['id']}' class='slider-value'>{$row['brightness']}%</span>
                                        </div>
                                      </td>";
                            } else {
                                // Show 'N/A' if it's not a Light Bulb
                                echo "<td>N/A</td>";
                            }

                            // Show Threshold Slider if it's a Sensor
                            if ($isSensor) {
                                echo "<td>
                                        <div class='slider-container'>
                                            <input type='range' id='threshold-{$row['id']}' class='threshold-slider' name='threshold' min='0' max='100' value='{$row['threshold']}' step='1'>
                                            <span id='threshold-value-{$row['id']}' class='slider-value'>{$row['threshold']}%</span>
                                      </div>
                                      </td>";
                            } else {
                                // Show 'N/A' if it's not a Sensor
                                echo "<td>N/A</td>";
                            }

                            // Actions (Edit, Delete)
                            echo "<td>
                                    <a href='update.php?id={$row['id']}' class='button'>Edit</a>
                                    <a href='delete.php?id={$row['id']}' class='button danger'>Delete</a>
                                  </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='6' class='text-center'>No devices found. Add a new device to get started.</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript to dynamically update slider values -->
    <script>
        // Update the brightness value dynamically
        document.querySelectorAll('.brightness-slider').forEach(function(slider) {
            slider.addEventListener('input', function() {
                const sliderId = this.id.split('-')[1]; // Get the device ID from the slider ID
                document.getElementById('brightness-value-' + sliderId).textContent = this.value + '%';
            });
        });

        // Update the threshold value dynamically
        document.querySelectorAll('.threshold-slider').forEach(function(slider) {
            slider.addEventListener('input', function() {
                const sliderId = this.id.split('-')[1]; // Get the device ID from the slider ID
                document.getElementById('threshold-value-' + sliderId).textContent = this.value + '%';
            });
        });
    </script>
</body>
</html>
