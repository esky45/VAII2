const express = require('express');
const cors = require('cors');

const app = express();
const port = 3000;

// Enable CORS for all routes

app.use(cors({origin: '*'}));
// Use body-parser middleware to parse incoming JSON requests
app.use(bodyParser.json());

// Simple user validation function
function validateUser(username, password) {
  
    return username.length >= 3 && password.length >= 8;
}

// Endpoint for handling form submissions
app.post('/signup', (req, res) => {
    const {username, password} = req.body;

    // Validate the user on the server
    if (validateUser(username, password)) {


        res.send('User registration successful!');
    } else {
       
        res.status(400).send('Invalid username or password');
    }
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});