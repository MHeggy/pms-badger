document.addEventListener('DOMContentLoaded', function () {
    // Initialize event listeners
    initializeEventListeners();
});

function initializeEventListeners() {
    // Event listener for sorting arrows
    document.getElementById('sortAsc').addEventListener('click', function () {
        sortProjects('asc');
        toggleSortArrows('asc');
    });
    document.getElementById('sortDesc').addEventListener('click', function () {
        sortProjects('desc');
        toggleSortArrows('desc');
    });
}

function updateProjectList(projects) {
    const projectList = document.getElementById('project_list');
    projectList.innerHTML = ''; // Clear the current list

    projects.forEach(project => {
        const projectRow = document.createElement('tr');
        projectRow.setAttribute('data-project-id', project.projectID);

        const assignedUsersHTML = project.assignedUsers.length
            ? '<ul>' + project.assignedUsers.map(user => `<li>${user}</li>`).join('') + '</ul>'
            : 'No users assigned.';

        projectRow.innerHTML = `
            <td>${project.projectNumber}</td>
            <td>${project.projectName}</td>
            <td>${project.statusName}</td>
            <td>${project.categoryNames}</td> <!-- Display categories -->
            <td>${project.dateAccepted}</td>
            <td>${assignedUsersHTML}</td>
        `;

        projectList.appendChild(projectRow);
    });
}

function sortProjects(order) {
    const projectList = document.getElementById('project_list');
    const rows = Array.from(projectList.querySelectorAll('tr:not(:first-child)')); // Exclude header row

    const sortedRows = rows.sort((a, b) => {
        // Split project number into year and number parts
        const aParts = a.cells[0].textContent.trim().split('-');
        const bParts = b.cells[0].textContent.trim().split('-');

        // Parse year and number parts into integers
        const aYear = parseInt(aParts[0]);
        const bYear = parseInt(bParts[0]);
        const aNumber = parseInt(aParts[1]);
        const bNumber = parseInt(bParts[1]);

        // Compare years first, then numbers
        if (aYear !== bYear) {
            return order === 'asc' ? aYear - bYear : bYear - aYear;
        } else {
            return order === 'asc' ? aNumber - bNumber : bNumber - aNumber;
        }
    });

    // Clear and re-append sorted rows
    projectList.innerHTML = ''; // Clear existing rows
    sortedRows.forEach(row => {
        projectList.appendChild(row);
    });
}

function toggleSortArrows(order) {
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');

    if (order === 'asc') {
        sortAsc.style.display = 'inline';
        sortDesc.style.display = 'none';
    } else {
        sortAsc.style.display = 'none';
        sortDesc.style.display = 'inline';
    }
}
