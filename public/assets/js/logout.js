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
    // Redirect to logout route
    window.location.href = "http://localhost:8080/logout";
}

// Display modal when page loads
window.onload = function() {
    displayModal();
};