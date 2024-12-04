<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="style.css" rel="stylesheet">
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet">
    <script crossorigin="anonymous"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <title>Online Orders</title>
</head>
<body>

<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" href="#">
        <span class="fs-4">EMP</span>
        <img alt="" height="25" src="bootstrap-icons-1.11.1/device-ssd-fill.svg" width="25">
    </a>

    <ul class="nav nav-pills">
        <li class="nav-item"><a aria-current="page" class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Projects</a></li>
        <li class="nav-item"><a class="nav-link" href="#">FAQs</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
    </ul>
</header>

<div class="container mt-5" id="mainContainer">
    <h2>Online Orders</h2>

    <!-- Authentication status -->
    <div id="authStatus" style="display: none;">
        <p>Welcome, <span id="loggedInUser"></span>! (<a href="#" onclick="logout()">Logout</a>)</p>
    </div>

    <!-- Sign-up and Login forms -->
    <div id="authForms">
        <!-- Sign-up form -->
        <div id="signupForm">
            <h3>Sign Up</h3>
            <div class="form-group">
                <label for="signupUsername">Username:</label>
                <input class="form-control" id="signupUsername" required type="text">
            </div>
            <div class="form-group">
                <label for="signupPassword">Password:</label>
                <input class="form-control" id="signupPassword" required type="password">
            </div>
            <button class="btn btn-success" onclick="signup()" type="button">Sign Up</button>
        </div>

        <hr>

        <!-- Sign-in form -->
        <div id="loginForm">
            <h3>Login</h3>
            <div class="form-group">
                <label for="loginUsername">Username:</label>
                <input class="form-control" id="loginUsername" required type="text">
            </div>
            <div class="form-group">
                <label for="loginPassword">Password:</label>
                <input class="form-control" id="loginPassword" required type="password">
            </div>
            <button class="btn btn-primary" onclick="login()" type="button">Login</button>
        </div>
    </div>

    <!-- CRUD operations form (visible only when logged in) -->
    <div id="crudForm" style="display: none;">
        <form id="orderForm">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input class="form-control" id="productName" required type="text">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input class="form-control" id="quantity" required type="number">
            </div>
            <button class="btn btn-primary" onclick="addOrder()" type="button">Add Order</button>
        </form>

        <hr>

        <table class="table">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="orderTableBody">
            <!-- Orders will be dynamically added here -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script>
    // Check if a user is already logged in
    const loggedInUser = localStorage.getItem('loggedInUser');
    if (loggedInUser) {
        document.getElementById('loggedInUser').textContent = loggedInUser;
        document.getElementById('authStatus').style.display = 'block';
        document.getElementById('authForms').style.display = 'none';
        document.getElementById('crudForm').style.display = 'block';
    }

    // Simulated user database
    const users = JSON.parse(localStorage.getItem('users')) || [];

    // Load orders from local storage
    let orders = JSON.parse(localStorage.getItem('orders')) || [];

    // Function to sign up a new user
    function signup() {
        const signupUsername = document.getElementById('signupUsername').value;
        const signupPassword = document.getElementById('signupPassword').value;

        // Simulate password hashing (replace with secure server-side hashing)
        const hashedPassword = CryptoJS.SHA256(signupPassword).toString();

        // Check if the username is already taken
        if (!users.some(user => user.username === signupUsername)) {
            // Add the new user to the simulated database
            users.push({
                username: signupUsername,
                password: hashedPassword
            });

            // Save the users array to local storage
            localStorage.setItem('users', JSON.stringify(users));

            // Clear the sign-up form
            document.getElementById('signupUsername').value = '';
            document.getElementById('signupPassword').value = '';

            alert('Sign up successful! Please log in.');
        } else {
            alert('Username already taken. Please choose a different username.');
        }
    }

    // Function to simulate user login
    function login() {
        // Function to simulate user login (continued)
        const loginUsername = document.getElementById('loginUsername').value;
        const loginPassword = document.getElementById('loginPassword').value;

        // Simulate password hashing (replace with secure server-side hashing)
        const hashedPassword = CryptoJS.SHA256(loginPassword).toString();

        // Check if the provided credentials are valid
        const user = users.find(u => u.username === loginUsername && u.password === hashedPassword);

        if (user) {
            // Save the logged-in user to local storage
            localStorage.setItem('loggedInUser', user.username);

            // Display authentication status and hide login/sign-up forms
            document.getElementById('loggedInUser').textContent = user.username;
            document.getElementById('authStatus').style.display = 'block';
            document.getElementById('authForms').style.display = 'none';
            document.getElementById('crudForm').style.display = 'block';

            // Load orders from local storage for the logged-in user
            orders = JSON.parse(localStorage.getItem('orders-' + user.username)) || [];

            // Update the table with the loaded orders
            updateOrderTable();
        } else {
            alert('Invalid credentials. Please try again.');
        }
    }

    // Function to log out the user
    function logout() {
        // Remove the logged-in user from local storage
        localStorage.removeItem('loggedInUser');

        // Reset the forms and display the login/sign-up forms
        document.getElementById('signupUsername').value = '';
        document.getElementById('signupPassword').value = '';
        document.getElementById('loginUsername').value = '';
        document.getElementById('loginPassword').value = '';

        // Clear the orders array
        orders = [];
        // Update local storage with the modified orders array
        localStorage.setItem('orders', JSON.stringify(orders));

        // Update the table with the modified orders array
        updateOrderTable();

        // Display the login/sign-up forms and hide the authentication status and CRUD form
        document.getElementById('authForms').style.display = 'block';
        document.getElementById('authStatus').style.display = 'none';
        document.getElementById('crudForm').style.display = 'none';
    }

    // Function to add a new order
    function addOrder() {
        const productName = document.getElementById('productName').value;
        const quantity = document.getElementById('quantity').value;

        if (productName && quantity) {
            // Create a new order object
            const order = {
                productName: productName,
                quantity: quantity
            };

            // Add the order to the orders array
            orders.push(order);

            // Update local storage with the modified orders array
            localStorage.setItem('orders-' + localStorage.getItem('loggedInUser'), JSON.stringify(orders));

            // Clear the form inputs
            document.getElementById('productName').value = '';
            document.getElementById('quantity').value = '';

            // Update the table with the new order
            updateOrderTable();
        } else {
            alert('Please fill in all fields');
        }
    }

    // Function to update the table with orders
    function updateOrderTable() {
        const tableBody = document.getElementById('orderTableBody');
        tableBody.innerHTML = '';

        // Iterate through the orders array and add rows to the table
        for (const order of orders) {
            const row = `<tr>
                    <td>${order.productName}</td>
                    <td>${order.quantity}</td>
                    <td><button class="btn btn-danger" onclick="deleteOrder('${order.productName}')">Delete</button></td>
                  </tr>`;
            tableBody.innerHTML += row;
        }
    }

    // Function to delete an order
    function deleteOrder(productName) {
        // Find the index of the order in the array
        const index = orders.findIndex(order => order.productName === productName);

        // Remove the order from the array
        orders.splice(index, 1);

        // Update local storage with the modified orders array
        localStorage.setItem('orders-' + localStorage.getItem('loggedInUser'), JSON.stringify(orders));

        // Update the table with the modified orders array
        updateOrderTable();
    }
</script>

</body>
</html>


