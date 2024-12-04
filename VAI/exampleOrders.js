// exampleServices.js

// Example services catalog
const exampleServices = [
    {
        name: 'Cabling',
        description: 'Professional cabling services for your electrical needs.',
    },
    {
        name: 'High Voltage Work',
        description: 'Specialized high voltage work performed by experienced electricians.',
    },
    {
        name: 'LAN Structure Cabling',
        description: 'Installation and maintenance of LAN structure cabling for network connectivity.',
    },
    {
        name: 'Programming Smart Lights',
        description: 'Smart lighting solutions tailored to your preferences and automation needs.',
    },
    {
        name: 'Security Systems',
        description: 'Installation and maintenance of security systems for your home or business.',
    },
    {
        name: 'Surge Protection',
        description: 'Surge protection for your home or business.',
    },
    {
        name: 'Switchboard Upgrades',
        description: 'Switchboard upgrades for your home or business.',
    },
    {
        name: 'Testing and Tagging',
        description: 'Testing and tagging of electrical equipment.',
    },
    {
        name: 'Thermal Imaging',
        description: 'Thermal imaging services for your home or business.',
    },
    {
        name: 'Underground Cabling',
        description: 'Underground cabling services for your home or business.',
    },
    {
        name: 'Wiring',
        description: 'Wiring services for your home or business.',
    },
];

// Function to open the example services modal
function openExampleServicesModal() {
    // Retrieve the modal element
    var exampleServicesModal = new bootstrap.Modal(document.getElementById('exampleServicesModal'));

    // Show the modal
    exampleServicesModal.show();

    // Populate the modal content with example services
    const modalBody = document.getElementById('exampleServicesModalBody');
    modalBody.innerHTML = '';

    exampleServices.forEach((service, index) => {
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item';
        listItem.innerHTML = `
            <h5>${service.name}</h5>
            <p>${service.description}</p>
            <div id="applyServiceContainer"">
            <button type="button" class="btn btn-success" onclick="applyExampleService(${index})">Apply Service</button>
            </div>
        `;
        modalBody.appendChild(listItem);
    });
}

// Function to apply an example service
function applyExampleService(index) {
    const selectedService = exampleServices[index];


    const orderNameInput = document.getElementById('productName');
    const orderDetailsInput = document.getElementById('details');

    if (orderNameInput && orderDetailsInput) {

        orderNameInput.value = selectedService.name;
        orderDetailsInput.value = selectedService.description;


        alert(`Applied Service: ${selectedService.name}`);
    } else {
        console.error("Failed to find order form elements.");
    }
}