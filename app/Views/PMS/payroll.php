<?php $pageTitle = "Timesheets"; ?>

<header>
    <?php include 'header.php' ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }

    #timesheet-container {
        margin-bottom: 50px;
    }

    .view-timesheets-card {
        margin-bottom: 20px;
    }

    .timesheet-table input {
        width: 100%;
    }

    .button-container {
        margin-top: 20px;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .table th {
        background-color: #007bff;
        color: white;
        text-align: center;
    }

    .table td {
        background-color: white;
    }

    .logo {
        width: 100px; /* Adjust logo size */
    }

    .btn-icon {
        display: flex;
        align-items: center;
    }

    .btn-icon img {
        width: 20px; /* Adjust icon size */
        margin-right: 5px;
    }

    .small-btn {
        font-size: 12px; /* Adjust font size */
        padding: 5px 10px; /* Adjust padding */
        margin: 0; /* Optional: Remove margin if not needed */
    }

    .alert {
        padding: 10px 20px; /* Reduce padding */
        margin-bottom: 10px; /* Space between alerts */
        font-size: 14px; /* Smaller font size */
    }
    
    .alert i {
        font-size: 18px; /* Icon size */
    }

    .alert .btn-close {
        margin-left: 10px; /* Space between message and close button */
    }

    .custom-alert {
        width: 300px; /* Set a fixed width for the alert */
        height: auto; /* Auto height */
        padding: 5px 15px; /* Reduce padding for a smaller box */
        margin: 10px; /* Space between alerts */
        font-size: 14px; /* Smaller font size */
        position: relative; /* Fix position to prevent shifting */
        z-index: 1050; /* Make sure alerts appear above other elements */
    }

    @media (max-width: 767px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }

        .card-body {
            padding: 15px;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        .table input {
            font-size: 14px;
            padding: 5px;
        }

        .form-control {
            font-size: 14px;
            padding: 10px;
        }

        .btn {
            font-size: 14px;
            padding: 10px;
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }
    }
</style>

<br><br>
<?php if (session()->get('success_message') || !empty($errorMessage)): ?>
    <div class="container">
        <?php if (session()->get('success_message')): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center custom-alert" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>
                    <?= session()->get('success_message') ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center custom-alert" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    <?= $errorMessage ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<!-- Section to allow user to view their own timesheets -->
<div class="container mt-3">
    <div class="card view-timesheets-card">
        <div class="card-body">
            <h5 class="card-title">View Timesheets</h5>
            <p class="card-text">Click below to view, edit, or delete your timesheets.</p>
            <a href="/timesheets/user/<?= $user->id ?>" class="btn btn-primary">My Timesheets</a>
        </div>
    </div>
</div>

<!-- Timesheet form -->
<div class="container mt-5" id="timesheet-container">
    <form id="timesheet-form" action="/submit-timesheets" method="post">
        <input type="hidden" id="user-id" name="user-id" value="<?= $userId; ?>">
        <div class="row mb-3">
            <label for="week" class="col-sm-2 col-form-label">Week</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="week" name="week" value="<?= esc($weekOf) ?>" required>
                <small id="date-error" class="text-danger"></small>
            </div>
        </div>

        <!-- Timesheet table -->
    <div class="table-responsive">
        <table class="table timesheet-table">
            <thead>
                <tr>
                    <th>Project Number</th>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                    <th>Total Hours</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="timesheet-rows">
            <?php if (isset($entries) && count($entries) > 0): ?>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><input type="text" class="form-control" name="projectNumber[]" value="<?= $entry['projectNumber'] ?>"></td>
                        <td><input type="text" class="form-control" name="projectName[]" value="<?= $entry['projectName'] ?>"></td>
                        <td><input type="text" class="form-control" name="activityDescription[]" value="<?= $entry['activityDescription'] ?>"></td>
                        <td><input type="number" class="form-control day-input" name="monday[]" value="<?= $entry['mondayHours'] ?>" step="0.01"></td>
                        <td><input type="number" class="form-control day-input" name="tuesday[]" value="<?= $entry['tuesdayHours'] ?>" step="0.01"></td>
                        <td><input type="number" class="form-control day-input" name="wednesday[]" value="<?= $entry['wednesdayHours'] ?>" step="0.01"></td>
                        <td><input type="number" class="form-control day-input" name="thursday[]" value="<?= $entry['thursdayHours'] ?>" step="0.01"></td>
                        <td><input type="number" class="form-control day-input" name="friday[]" value="<?= $entry['fridayHours'] ?>" step="0.01"></td>
                        <td><input type="number" class="form-control day-input" name="saturday[]" value="<?= $entry['saturdayHours'] ?>" step="0.01"></td>
                        <td><input type="number" class="form-control day-input" name="sunday[]" value="<?= $entry['sundayHours'] ?>" step="0.01"></td>
                        <td><input type="text" class="form-control total-hours" name="totalHours[]" value="<?= $entry['totalHours'] ?>" readonly></td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row small-btn">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <!-- Existing rows with unique identifiers -->
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row small-btn">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]" value="13-000" readonly></td>
                    <td><input type="text" class="form-control" name="projectName[]" value="General Office" readonly></td>
                    <td><input type="text" class="form-control" name="activityDescription[]" value="Phone Calls / Accounting / emails / cleaning" readonly></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                    <td></td>
            </tr>
            </tbody>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-end"><strong>Total Hours for the Week:</strong></td>
                    <td><input type="text" id="weekly-total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
        <!-- Add Row Button -->
        <div class="button-container">
            <button type="button" class="btn btn-success" id="add-row"><i class="fas fa-plus"></i> Add Row</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit</button>
        </div>
    </form>
</div>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>

<script>
let rowCount = 8; // Adjusting row count for existing rows

function calculateRowTotal(row) {
    let totalHours = 0;
    const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    daysOfWeek.forEach(day => {
        const input = row.querySelector(`input[name="${day}[]"]`);
        if (input && input.value !== '') {
            totalHours += parseFloat(input.value);
        }
    });
    row.querySelector('.total-hours').value = totalHours.toFixed(2);
}

$(document).ready(function () {
    $('#add-row').click(function () {
        // Create a new row
        const newRow = `
            <tr>
                <td><input type="text" class="form-control" name="projectNumber[]"></td>
                <td><input type="text" class="form-control" name="projectName[]"></td>
                <td><input type="text" class="form-control" name="activityDescription[]"></td>
                <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                </td>
            </tr>
        `;

        // Insert the new row before the row with project number '13-000'
        const newRowElement = $(newRow); // Create jQuery object for new row
        $('#timesheet-rows tr').filter(function () {
            return $(this).find('input[name="projectNumber[]"]').val() === '13-000'; // Check the project number input
        }).first().before(newRowElement); // Insert the new row before the found row
        
        // Call addEventListenersToRow function on the newly added row
        addEventListenersToRow(newRowElement[0]);
    });
});

// Function to calculate all totals
function calculateAllTotals() {
    let weeklyTotal = 0;
    document.querySelectorAll('#timesheet-rows tr').forEach(row => {
        if (!row.querySelector('input[name="projectNumber[]"]').readonly) { // Exclude fixed row
            calculateRowTotal(row);
            const rowTotal = parseFloat(row.querySelector('.total-hours').value) || 0;
            weeklyTotal += rowTotal;
        }
    });
    document.getElementById('weekly-total').value = weeklyTotal.toFixed(2);
}

// Function to add event listeners to a specific row
function addEventListenersToRow(row) {
    if (row) {
        row.querySelectorAll('.day-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateRowTotal(row);
                calculateAllTotals();
            });
        });

        const removeButton = row.querySelector('.remove-row');
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                row.remove();
                calculateAllTotals();
            });
        }
    }
}

// Initial event listener attachment for existing rows
document.querySelectorAll('#timesheet-rows tr').forEach(row => {
    addEventListenersToRow(row);
});

// This function should be called once when the document is ready
calculateAllTotals(); // Initial calculation

function isMonday(date) {
    return date.getDay() === 0;
}

function setMondayRestriction() {
    const weekInput = document.getElementById('week');
    const today = new Date();
    
    // Set the minimum date to the current date or the nearest past Monday
    while (!isMonday(today)) {
        today.setDate(today.getDate() - 1);
    }
    const minDate = today.toISOString().split('T')[0];
    weekInput.setAttribute('min', minDate);
    
    // Set the maximum date to 1 year from the current Monday (optional)
    const maxDate = new Date(today);
    maxDate.setFullYear(maxDate.getFullYear() + 1);
    weekInput.setAttribute('max', maxDate.toISOString().split('T')[0]);

    // Listen for the user to change the date input
    weekInput.addEventListener('change', function () {
        const selectedDate = new Date(weekInput.value);
        if (!isMonday(selectedDate)) {
            alert('Please select a Monday.');
            weekInput.value = ''; // Clear the invalid selection
        }
    });
}

// Initialize the restriction on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateAllTotals(); // Initial calculation
    setMondayRestriction();
});
</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>