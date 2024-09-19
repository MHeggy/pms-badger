document.addEventListener('DOMContentLoaded', function () {
    initializeEventListeners();
    displayActiveFilters(); // Ensure this is called after DOM is loaded
});

// Function to display active filters
function displayActiveFilters() {
    const searchParams = new URLSearchParams(window.location.search);
    const status = searchParams.get('status');
    const category = searchParams.get('category');

    const activeFilters = document.getElementById('activeFilters');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    if (status) {
        statusFilter.classList.remove('d-none');
        document.getElementById('statusName').textContent = getStatusName(status);
    }

    if (category) {
        categoryFilter.classList.remove('d-none');
        document.getElementById('categoryName').textContent = getCategoryName(category);
    }

    if (status || category) {
        activeFilters.classList.remove('d-none');
    }
}

// Helper functions to get names
function getStatusName(status) {
    switch (status) {
        case '1': return 'In Progress';
        case '2': return 'Completed';
        case '3': return 'Cancelled';
        case '4': return 'Postponed';
        default: return 'Unknown';
    }
}

function getCategoryName(categoryId) {
    const categories = {
        '1': 'Construction Staking',
        '2': 'Site Design',
        '3': 'Drainage Design',
        '4': 'Architectural Design',
        '5': 'Building Design'
    };
    return categories[categoryId] || 'Unknown';
}

// Initialize event listeners
function initializeEventListeners() {
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');
    const clearStatus = document.getElementById('clearStatus');
    const clearCategory = document.getElementById('clearCategory');

    sortAsc.addEventListener('click', () => sortWork('asc'));
    sortDesc.addEventListener('click', () => sortWork('desc'));

    clearStatus.addEventListener('click', () => clearFilter('status'));
    clearCategory.addEventListener('click', () => clearFilter('category'));
}

// Function to clear specific filter
function clearFilter(filterType) {
    const params = new URLSearchParams(window.location.search);
    params.delete(filterType); // Remove filter from URL query parameters
    window.location.search = params.toString(); // Reload page with updated parameters
}

// Function to sort work based on project number
function sortWork(order) {
    const workList = document.getElementById('project_list');
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