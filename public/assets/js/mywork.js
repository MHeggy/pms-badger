document.addEventListener('DOMContentLoaded', function () {
    initializeEventListeners();
});

// Function to sort work based on work number
function sortWork(order) {
    const workList = document.getElementById('work_list');
    const rows = Array.from(workList.querySelectorAll('tr'));

    const sortedRows = rows.sort((a, b) => {
        const aParts = a.cells[0].textContent.trim().split('-');
        const bParts = b.cells[0].textContent.trim().split('-');

        const aYear = parseInt(aParts[0]);
        const bYear = parseInt(bParts[0]);
        const aNumber = parseInt(aParts[1]);
        const bNumber = parseInt(bParts[1]);

        if (aYear !== bYear) {
            return order === 'asc' ? aYear - bYear : bYear - aYear;
        } else {
            return order === 'asc' ? aNumber - bNumber : bNumber - aNumber;
        }
    });

    workList.innerHTML = '';
    sortedRows.forEach(row => workList.appendChild(row));
}
