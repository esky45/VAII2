// modals.js
//   // Clear the modal body
//     modalBody.innerHTML = '';
//
//     // Create a new list item for each service
//     services.forEach((service, index) => {
//         const listItem = document.createElement('li');
//         listItem.className = 'list-group-item';
//         listItem.innerHTML = `
//             <h5>${service.name}</h5>
//             <p>${service.description}</p>
//             <div id="applyServiceContainer"">
//             <button type="button" class="btn btn-success" onclick="applyService(${index})">Apply Service</button>
//             </div>
//         `;


// Function to show the sign-up modal
function showSignupModal() {
    $('#signupModal').modal('show');
}

// Function to hide the sign-up modal
function hideSignupModal() {
    $('#signupModal').modal('hide');
}

// Function to show the login modal
function showLoginModal() {
    $('#loginModal').modal('show');
}

// Function to hide the login modal
function hideLoginModal() {
    $('#loginModal').modal('hide');
}