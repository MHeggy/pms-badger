// When project name is clicked.
document.querySelectorAll('#project_list tr').forEach(row => {
    row.addEventListener('click', function () {
        const projectId = this.dataset.projectId;
        fetchProjectDetails(projectId);
    });
});

function fetchProjectDetails(projectId) {
    fetch('/projectDetails/' + projectId)
        .then(response => response.json())
        .then(data => {
            const project = data.details;
            console.log(data);
            document.getElementById('projectDetails').innerHTML = `
                    <h2>Project Details</h2>
                    <p>Project Name: ${project.projectName}</p>
                    <p>Project Number: ${project.projectID}</p>
                    <p>Status: ${project.statusName}</p>
                    <p>Description: [Placeholder]</p>
                `;
            $('#projectDetailsModal').modal('show'); // Show the modal using jQuery
        });
}

// Close the modal when the close button is clicked
document.querySelector('.btn-close').addEventListener('click', function() {
    $('#projectDetailsModal').modal('hide'); // Hide the modal using jQuery
});

// Close the modal when the user clicks outside of it
$('#projectDetailsModal').on('hidden.bs.modal', function () {
    $('#projectDetailsModal').modal('hide'); // Hide the modal using jQuery
});