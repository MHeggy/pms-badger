document.addEventListener('DOMContentLoaded', function () {
    // Initialize event listeners
    initializeEventListeners();
});

// function updateProjectList(projects) {
//     const projectList = document.getElementById('project_list');
//     projectList.innerHTML = ''; // Clear the current list

//     projects.forEach(project => {
//         const projectRow = document.createElement('tr');
//         projectRow.setAttribute('data-project-id', project.projectID);

//         // Check if assignedUsers is defined and not empty
//         const assignedUsersHTML = project.assignedUsers && project.assignedUsers.length
//             ? '<ul>' + project.assignedUsers.map(user => `<li>${user.username}</li>`).join('') + '</ul>'
//             : 'No users assigned.';

//         projectRow.innerHTML = `
//             <td>${project.projectNumber}</td>
//             <td>${project.projectName}</td>
//             <td>${project.statusName}</td>
//             <td>${project.categoryNames ? project.categoryNames.split(',').join(', ') : ''}</td>
//             <td>${project.dateAccepted}</td>
//             <td>${assignedUsersHTML}</td>
//         `;

//         projectList.appendChild(projectRow);
//     });
// }

// // Add this function to your existing mywork.js file
// function fetchFilteredProjects(status) {
//     fetch(`/myWork/filter?status=${encodeURIComponent(status)}`)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok ' + response.statusText);
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log('Filtered Projects Data:', data);
//             updateProjectList(data.projects);
//         })
//         .catch(error => {
//             console.error('There was a problem with the fetch operation:', error);
//             alert('An error occurred while fetching projects. Please try again.');
//         });
// }

// Update the initializeEventListeners function
// function initializeEventListeners() {
//     // Add event listener for filter form
//     document.getElementById('filterForm').addEventListener('submit', function (e) {
//         e.preventDefault();
//         const status = document.getElementById('status').value;
//         fetchFilteredProjects(status);
//     });
// }

// Function to sort projects based on project number
// function sortProjects(order) {
//     const projectList = document.getElementById('project_list');
//     const rows = Array.from(projectList.querySelectorAll('tr:not(:first-child)')); // Exclude header row

//     const sortedRows = rows.sort((a, b) => {
//         // Split project number into year and number parts
//         const aParts = a.cells[0].textContent.trim().split('-');
//         const bParts = b.cells[0].textContent.trim().split('-');

//         // Parse year and number parts into integers
//         const aYear = parseInt(aParts[0]);
//         const bYear = parseInt(bParts[0]);
//         const aNumber = parseInt(aParts[1]);
//         const bNumber = parseInt(bParts[1]);

//         // Compare years first, then numbers
//         if (aYear !== bYear) {
//             return order === 'asc' ? aYear - bYear : bYear - aYear;
//         } else {
//             return order === 'asc' ? aNumber - bNumber : bNumber - aNumber;
//         }
//     });

//     // Clear and re-append sorted rows
//     projectList.innerHTML = ''; // Clear current rows
//     sortedRows.forEach(row => {
//         projectList.appendChild(row);
//     });
// }