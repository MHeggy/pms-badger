document.addEventListener('DOMContentLoaded', function () {
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');

    // Event listeners for sorting
    sortAsc.addEventListener('click', function () {
        sortProjects('asc');
        toggleSortArrows('asc');
    });
    sortDesc.addEventListener('click', function () {
        sortProjects('desc');
        toggleSortArrows('desc');
    });

    // Initial display based on default sort order
    const defaultOrder = 'asc'; // or get from some configuration
    if (defaultOrder === 'asc') {
        sortAsc.classList.add('active');
        sortDesc.classList.remove('active');
    } else {
        sortAsc.classList.remove('active');
        sortDesc.classList.add('active');
    }
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
    const rows = Array.from(projectList.querySelectorAll('tr'));
    const headerRow = rows.shift();

    const sortedRows = rows.sort((a, b) => {
        const aText = a.cells[0].textContent.trim();
        const bText = b.cells[0].textContent.trim();

        const [aYear, aNumber] = aText.split('-').map(Number);
        const [bYear, bNumber] = bText.split('-').map(Number);

        if (aYear !== bYear) {
            return order === 'asc' ? aYear - bYear : bYear - aYear;
        } else {
            return order === 'asc' ? aNumber - bNumber : bNumber - aNumber;
        }
    });

    projectList.innerHTML = '';
    projectList.appendChild(headerRow);
    sortedRows.forEach(row => {
        projectList.appendChild(row);
    });
}

function toggleSortArrows(order) {
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');

    if (order === 'asc') {
        sortAsc.classList.add('active');
        sortDesc.classList.remove('active');
    } else {
        sortAsc.classList.remove('active');
        sortDesc.classList.add('active');
    }
}