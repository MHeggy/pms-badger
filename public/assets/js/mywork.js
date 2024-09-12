document.addEventListener('DOMContentLoaded', function () {
    initializeEventListeners();

    // Display active filters on page load
    displayActiveFilters();
});

// Function to display active filters
function displayActiveFilters() {
    const status = new URLSearchParams(window.location.search).get('status');
    const category = new URLSearchParams(window.location.search).get('category');
    
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

// Helper function to get status name
function getStatusName(status) {
    switch (status) {
        case '1': return 'In Progress';
        case '2': return 'Completed';
        case '3': return 'Cancelled';
        case '4': return 'Postponed';
        default: return 'Unknown';
    }
}

// Helper function to get category name (you may need to adjust this to fetch names from your data)
function getCategoryName(categoryId) {
    // Example category names, replace with actual data retrieval logic if needed
    const categories = {
        '1': 'Category 1',
        '2': 'Category 2',
        '3': 'Category 3',
        '4': 'Category 4'
    };
    return categories[categoryId] || 'Unknown';
}

// Initialize event listeners for sorting and clearing filters
function initializeEventListeners() {
    const filterToggle = document.getElementById('filterToggle');
    const filterOptions = document.getElementById('filterOptions');
    const clearStatus = document.getElementById('clearStatus');
    const clearCategory = document.getElementById('clearCategory');
    const clearSearchButton = document.getElementById('clearSearchButton');
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');

    // Toggle filter options
    filterToggle.addEventListener('click', () => {
        filterOptions.classList.toggle('collapse');
    });

    // Clear status filter
    clearStatus.addEventListener('click', () => clearFilter('status'));

    // Clear category filter
    clearCategory.addEventListener('click', () => clearFilter('category'));

    // Clear search input
    clearSearchButton.addEventListener('click', () => {
        const searchInput = document.getElementById('search');
        searchInput.value = '';
        searchInput.focus();
        updateActiveFilters();
    });

    // Sorting event listeners
    sortAsc.addEventListener('click', () => sortWork('asc'));
    sortDesc.addEventListener('click', () => sortWork('desc'));
}

// Function to clear a specific filter
function clearFilter(filterType) {
    const params = new URLSearchParams(window.location.search);
    params.delete(filterType);
    window.location.search = params.toString();
}

// Function to sort work based on work number
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

// Function to update the active filters display
function updateActiveFilters() {
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusName = document.getElementById('statusName');
    const categoryName = document.getElementById('categoryName');

    const statusSelect = document.getElementById('status');
    const categorySelect = document.getElementById('category');
    const searchInput = document.getElementById('search');
    
    if (statusSelect.value) {
        statusName.textContent = statusSelect.options[statusSelect.selectedIndex].text;
        statusFilter.classList.remove('d-none');
    } else {
        statusFilter.classList.add('d-none');
    }

    if (categorySelect.value) {
        categoryName.textContent = categorySelect.options[categorySelect.selectedIndex].text;
        categoryFilter.classList.remove('d-none');
    } else {
        categoryFilter.classList.add('d-none');
    }

    if (searchInput.value) {
        clearSearchButton.classList.add('active');
    } else {
        clearSearchButton.classList.remove('active');
    }
}