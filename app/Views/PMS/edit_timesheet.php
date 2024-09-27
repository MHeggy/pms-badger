<?php esc($pageTitle = "Edit Timesheet") ?>

<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
    .container {
        margin: 0 auto; /* Center the container */
        padding-top: 20px; /* Space above content */
    }
    .table th, .table td {
        vertical-align: middle;
        text-align: center; /* Center table content */
    }
    .table th {
        background-color: #007bff; /* Header background color */
        color: white; /* Header text color */
    }
    .table td {
        background-color: white; /* Row background color */
    }
    .remove-row.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .button-container {
        margin-top: 20px;
        text-align: left; /* Align buttons to the right */
    }
    .btn-icon {
        margin-right: 5px; /* Space between icon and text */
    }
</style>

<div class="container mt-5">
    <?php if ($info_message = session()->get('info_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc($info_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <button onclick="goBack()" class="btn btn-primary btn-back">
        <i class="fas fa-arrow-left btn-icon"></i> Go Back
    </button>
    <br><br>

    <form id="timesheet-form" action="/timesheets/update" method="post">
        <input type="hidden" name="id" value="<?= esc($timesheet['timesheetID']) ?>">

        <div class="row mb-3">
            <label for="week" class="col-sm-2 col-form-label">Week</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="week" name="week" value="<?= esc($timesheet['weekOf']) ?>" required>
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
                    <?php if (!empty($entries)): ?>
                        <?php foreach ($entries as $entry): ?>
                            <tr>
                                <td><input type="text" class="form-control" name="projectNumber[]" value="<?= esc($entry['projectNumber']) ?>"></td>
                                <td><input type="text" class="form-control" name="projectName[]" value="<?= esc($entry['projectName']) ?>"></td>
                                <td><input type="text" class="form-control" name="activityDescription[]" value="<?= esc($entry['activityDescription']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="monday[]" step="0.01" value="<?= esc($entry['mondayHours']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01" value="<?= esc($entry['tuesdayHours']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01" value="<?= esc($entry['wednesdayHours']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01" value="<?= esc($entry['thursdayHours']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="friday[]" step="0.01" value="<?= esc($entry['fridayHours']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01" value="<?= esc($entry['saturdayHours']) ?>"></td>
                                <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01" value="<?= esc($entry['sundayHours']) ?>"></td>
                                <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly value="<?= esc($entry['totalHours']) ?>"></td>
                                <td><button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash-alt"></i> Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Initial Row -->
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
                            <td><button type="button" class="btn btn-danger remove-row disabled"><i class="fas fa-trash-alt"></i> Remove</button></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10" class="text-end"><strong>Total Hours for the Week:</strong></td>
                        <td><input type="text" id="weekly-total" class="form-control" readonly></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Add Row and Update Button -->
        <div class="button-container">
            <button type="button" id="add-row" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Add Row
            </button>
            <button type="submit" class="btn btn-success ms-2">Update Timesheet</button>
        </div>
    </form>
</div>

<script>
    // Function to calculate total hours for a specific row
    function calculateRowTotal(row) {
        let totalHours = 0;
        const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        daysOfWeek.forEach(day => {
            const input = row.querySelector(`[name="${day}[]"]`);
            if (input.value) {
                totalHours += parseFloat(input.value);
            }
        });
        row.querySelector('.total-hours').value = totalHours.toFixed(2); // Update row's total hours
    }

    // Function to calculate the overall weekly total
    function calculateAllTotals() {
        let weeklyTotal = 0;
        document.querySelectorAll('#timesheet-rows tr').forEach(row => {
            calculateRowTotal(row); // Calculate total for the current row
            const rowTotal = parseFloat(row.querySelector('.total-hours').value) || 0;
            weeklyTotal += rowTotal; // Accumulate to weekly total
        });
        document.getElementById('weekly-total').value = weeklyTotal.toFixed(2); // Update the weekly total input
    }

    // Function to add event listeners for row inputs
    function addEventListenersToRow(row) {
        row.querySelectorAll('.day-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateRowTotal(row); // Calculate total for this row on input
                calculateAllTotals(); // Recalculate the weekly total
            });
        });
        row.querySelector('.remove-row').addEventListener('click', () => {
            if (!row.querySelector('.remove-row').classList.contains('disabled')) {
                row.remove(); // Remove the row
                calculateAllTotals(); // Recalculate totals after removing
            }
        });
    }

    // Event listener for adding new rows
    document.getElementById('add-row').addEventListener('click', function () {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
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
            <td><button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash-alt"></i> Remove</button></td>
        `;
        document.getElementById('timesheet-rows').insertBefore(newRow, document.getElementById('timesheet-rows').lastElementChild);
        addEventListenersToRow(newRow); // Add event listeners to the new row
        calculateAllTotals(); // Recalculate totals after adding
    });

    // Initialize event listeners for existing rows
    document.querySelectorAll('#timesheet-rows tr').forEach(row => {
        addEventListenersToRow(row);
    });

    // Back button functionality
    function goBack() {
        window.history.back();
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateAllTotals(); // Calculate totals on page load
    });
</script>