<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Home Interface</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        #device-menu {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .device {
            width: 60px;
            height: 60px;
            background-color: #eee;
            margin: 10px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .device:hover {
            transform: scale(1.1);
        }

        .lightbulb {
            background-color: #ffeb3b;
        }

        .sensor {
            background-color: #e53935;
        }

        #house-layout {
            position: relative;
            width: 80%;
            height: 80vh;
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 50%);
            grid-template-rows: repeat(2, 50%);
            grid-gap: 10px;
            background-color: #f0f0f0;
            border: 5px solid #ccc;
            border-radius: 20px;
            box-sizing: border-box;
        }

        .room {
            position: relative;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        .room-on {
            background-color: #f3f4f6;
        }

        .room-1 { grid-column: 1; grid-row: 1; }
        .room-2 { grid-column: 2; grid-row: 1; }
        .room-3 { grid-column: 1; grid-row: 2; }
        .room-4 { grid-column: 2; grid-row: 2; }

        .device-in-room {
            position: absolute;
            width: 50px;
            height: 50px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }

        .device-in-room.lightbulb {
            background-color: yellow;
        }

        .device-in-room.sensor {
            background-color: red;
        }

        .room .device-in-room {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .room .device-in-room:hover {
            transform: scale(1.2);
        }

        /* Light effect styles */
        .light-effect {
            position: absolute;
            width: 200px;
            height: 200px;
            background-color: rgba(255, 255, 0, 0.5);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.5s ease-out;
            pointer-events: none;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .lightbulb-on .light-effect {
            opacity: 1;
        }
    </style>
</head>
<body>

    <div id="device-menu">
        <div class="device lightbulb" id="lightbulb">💡</div>
        <div class="device sensor" id="sensor">📟</div>
    </div>

    <div id="house-layout">
        <div class="room room-1" id="room-1">Living Room</div>
        <div class="room room-2" id="room-2">Kitchen</div>
        <div class="room room-3" id="room-3">Bedroom</div>
        <div class="room room-4" id="room-4">Bathroom</div>
    </div>

    <script>
    let selectedDevice = null;
    let currentRoom = null;

    // Handle device dragging
    document.querySelectorAll('.device').forEach(deviceElement => {
        deviceElement.draggable = true;

        deviceElement.addEventListener('dragstart', function (e) {
            selectedDevice = deviceElement;
        });
    });

    // Handle dropping the device in a room
    document.querySelectorAll('.room').forEach(room => {
        room.addEventListener('dragover', function (e) {
            e.preventDefault();
        });

        room.addEventListener('drop', function (e) {
            if (selectedDevice) {
                const deviceType = selectedDevice.className.split(' ')[1];
                const roomRect = room.getBoundingClientRect();
                const posX = e.clientX - roomRect.left - 25; // Positioning the device
                const posY = e.clientY - roomRect.top - 25;

                createDeviceInRoom(deviceType, posX, posY, room);
            }
        });
    });

    function createDeviceInRoom(deviceType, x, y, room) {
        const device = document.createElement('div');
        device.classList.add('device-in-room', deviceType);
        device.style.left = `${x}px`;
        device.style.top = `${y}px`;

        if (deviceType === 'lightbulb') {
            device.textContent = '💡';
        } else if (deviceType === 'sensor') {
            device.textContent = '📟';
        }

        room.appendChild(device);

    
        const lightEffect = document.createElement('div');
        lightEffect.classList.add('light-effect');
        room.appendChild(lightEffect);

        // Click event to control the device
        device.addEventListener('click', function () {
            if (device.classList.contains('lightbulb-on')) {
                // Turn off the light
                device.classList.remove('lightbulb-on');
                room.classList.remove('room-on');
                lightEffect.style.opacity = 0;
            } else {
                // Turn on the light
                device.classList.add('lightbulb-on');
                room.classList.add('room-on');
                lightEffect.style.opacity = 1;
            }
        });

        // Make device movable within the room
        device.addEventListener('mousedown', function (e) {
            const offsetX = e.clientX - device.getBoundingClientRect().left;
            const offsetY = e.clientY - device.getBoundingClientRect().top;

            // Ensure the initial position calculation is correct
            const startX = device.offsetLeft;
            const startY = device.offsetTop;

            // Function to move device on mouse move
            function moveDevice(e) {
                device.style.left = `${startX + e.clientX - offsetX}px`;
                device.style.top = `${startY + e.clientY - offsetY}px`;
            }

       
            function stopMovingDevice() {
                document.removeEventListener('mousemove', moveDevice);
                document.removeEventListener('mouseup', stopMovingDevice);
            }

          
            document.addEventListener('mousemove', moveDevice);
            document.addEventListener('mouseup', stopMovingDevice);
        });
    }
</script>
