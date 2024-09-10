// Get the modal
var modal = document.getElementById("logoutModal");

// Function to display the modal
function displayModal() {
    modal.style.display = "block";
}

// Function to close the modal
function closeModal() {
    modal.style.display = "none";
}

// Function to logout
function logout() {
    // redirect to the logout route.
    window.location.href = '/logout';
}

document.addEventListener('DOMContentLoaded', function() {
    // Get the user menu and dropdown menu
    const userMenu = document.querySelector('.user-menu');
    const dropdownMenu = userMenu.querySelector('.dropdown-menu');

    // Function to toggle dropdown menu
    function toggleDropdown() {
        dropdownMenu.classList.toggle('show');
    }

    // Show dropdown menu when clicking on the user menu
    userMenu.addEventListener('click', function(event) {
        // Check if the clicked element is the user name
        if (event.target.classList.contains('user-name')) {
            toggleDropdown();
        }
    });

    // Close dropdown menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!userMenu.contains(event.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});

document.getElementById('taskbarToggle').addEventListener('click', function() {
    this.classList.toggle('active');
    document.getElementById('taskbarItems').classList.toggle('show');
});
