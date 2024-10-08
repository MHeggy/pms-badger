document.addEventListener('DOMContentLoaded', function () {
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');

    // Ensure the sort arrows exist and listeners are added
    if (sortAsc && sortDesc) {
        sortAsc.addEventListener('click', function () {
            sortProjects('asc');
            toggleSortArrows('asc');
        });
        sortDesc.addEventListener('click', function () {
            sortProjects('desc');
            toggleSortArrows('desc');
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const clearSearchButton = document.getElementById('clearSearchButton');
    const searchButton = document.getElementById('searchButton');

    // Toggle visibility of the clear button based on input
    searchInput.addEventListener('input', function () {
        if (searchInput.value.trim() !== '') {
            clearSearchButton.style.display = 'block'; // Show clear button
            searchButton.style.display = 'none'; // Hide search button
        } else {
            clearSearchButton.style.display = 'none'; // Hide clear button
            searchButton.style.display = 'block'; // Show search button
        }
    });

    // Clear search input when clear button is clicked
    clearSearchButton.addEventListener('click', function () {
        searchInput.value = ''; // Clear the input
        searchInput.dispatchEvent(new Event('input')); // Trigger input event to update button visibility
        // Optionally submit the form to clear search results
        document.getElementById('searchForm').submit(); // Uncomment this line if you want to refresh the results
    });

    // Initialize sorting event listeners
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

function sortProjects(order) {
    const projectList = document.getElementById('project_list');
    const rows = Array.from(projectList.querySelectorAll('tr'));

    const sortedRows = rows.sort((a, b) => {
        const aText = a.cells[0].textContent.trim();
        const bText = b.cells[0].textContent.trim();

        // Split projectNumber into year and sequential number
        const [aYear, aNumber] = aText.split('-').map(Number);
        const [bYear, bNumber] = bText.split('-').map(Number);

        console.log(`Sorting ${aText} and ${bText} -> aYear: ${aYear}, aNumber: ${aNumber}, bYear: ${bYear}, bNumber: ${bNumber}`);

        // Sorting logic
        if (aYear !== bYear) {
            return order === 'asc' ? aYear - bYear : bYear - aYear;
        } else {
            return order === 'asc' ? aNumber - bNumber : bNumber - aNumber;
        }
    });

    // Clear the current list and append the sorted rows
    projectList.innerHTML = '';
    sortedRows.forEach(row => projectList.appendChild(row));
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

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const clearSearchButton = document.getElementById('clearSearchButton');

    searchInput.addEventListener('input', function () {
        clearSearchButton.style.display = searchInput.value ? 'block' : 'none';
    });

    // Clear the search input when the X button is clicked
    clearSearchButton.addEventListener('click', function() {
        searchInput.value = '';
        clearSearchButton.style.display = 'none';
        document.getElementById('searchForm').submit(); // Optionally auto-submit after clearing
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status');
    const categorySelect = document.getElementById('category');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const activeFilters = document.getElementById('activeFilters');
    
    // Function to update filter display based on selection
    function updateFilters() {
        const statusValue = statusSelect.value;
        const categoryValue = categorySelect.value;

        // Update status filter display
        if (statusValue) {
            const statusText = statusSelect.options[statusSelect.selectedIndex].text;
            document.getElementById('statusName').textContent = statusText;
            statusFilter.classList.remove('d-none');
        } else {
            statusFilter.classList.add('d-none');
        }

        // Update category filter display
        if (categoryValue) {
            const categoryText = categorySelect.options[categorySelect.selectedIndex].text;
            document.getElementById('categoryName').textContent = categoryText;
            categoryFilter.classList.remove('d-none');
        } else {
            categoryFilter.classList.add('d-none');
        }

        // Toggle the entire filter badge container
        if (statusValue || categoryValue) {
            activeFilters.classList.remove('d-none');
        } else {
            activeFilters.classList.add('d-none');
        }
    }

    // Initially set filters based on current selection
    updateFilters();

    // Clear status filter
    document.getElementById('clearStatus').addEventListener('click', function() {
        statusSelect.value = '';
        updateFilters();
        document.getElementById('filterForm').submit(); // Optionally auto-submit after clearing
    });

    // Clear category filter
    document.getElementById('clearCategory').addEventListener('click', function() {
        categorySelect.value = '';
        updateFilters();
        document.getElementById('filterForm').submit(); // Optionally auto-submit after clearing
    });

    // Update filters on dropdown change
    statusSelect.addEventListener('change', updateFilters);
    categorySelect.addEventListener('change', updateFilters);
});