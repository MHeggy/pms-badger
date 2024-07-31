<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = "Timesheets"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #timesheet-container {
            margin-bottom: 50px;
        }

        .view-timesheets-card {
            margin-bottom: 20px;
        }

        .timesheet-table input {
            width: 100%;
        }

        .remove-row.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>
<br><br>
<!-- Section to allow user to view their own timesheets -->
<div class="container mt-3">
    <div class="card view-timesheets-card">
        <div class="card-body">
            <h5 class="card-title">View Timesheets</h5>
            <p class="card-text">Click below to view your timesheets.</p>
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
                <input type="date" class="form-control" id="week" name="week" required>
            </div>
        </div>

        <!-- Timesheet table -->
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
                <!-- First row (cannot be removed) -->
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[]"></td>
                    <td><input type="text" class="form-control" name="projectName[]"></td>
                    <td><input type="text" class="form-control" name="description[]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row disabled">Remove</button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" class="text-end"><strong>Total Hours for the Week:</strong></td>
                    <td><input type="text" id="weekly-total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Add Row Button -->
        <div class="button-container">
            <button type="button" id="add-row" class="btn btn-secondary">Add Row</button>
        </div>

        <!-- Submit button -->
        <div class="button-container">
            <button id="submit-timesheet" type="submit" class="btn btn-primary">Submit Timesheet</button>
        </div>
    </form>
</div>

<!-- Scripts -->
<script>
    function calculateRowTotal(row) {
        let totalHours = 0;
        const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        daysOfWeek.forEach(day => {
            const input = row.querySelector(`[name="${day}[]"]`);
            if (input.value !== '') {
                totalHours += parseFloat(input.value);
            }
        });
        row.querySelector('.total-hours').value = totalHours.toFixed(2);
    }

    function calculateAllTotals() {
        let weeklyTotal = 0;
        document.querySelectorAll('#timesheet-rows tr').forEach(row => {
            calculateRowTotal(row);
            const rowTotal = parseFloat(row.querySelector('.total-hours').value) || 0;
            weeklyTotal += rowTotal;
        });
        document.getElementById('weekly-total').value = weeklyTotal.toFixed(2);
    }

    document.querySelectorAll('.day-input').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            calculateRowTotal(row);
            calculateAllTotals();
        });
    });

    document.querySelectorAll('#timesheet-rows .remove-row').forEach(button => {
        button.addEventListener('click', () => {
            if (!button.classList.contains('disabled')) {
                button.closest('tr').remove();
                calculateAllTotals();
            }
        });
    });

    document.getElementById('add-row').addEventListener('click', () => {
        const newRow = document.querySelector('#timesheet-rows tr').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('.remove-row').classList.remove('disabled');
        document.querySelector('#timesheet-rows').appendChild(newRow);
        newRow.querySelectorAll('.day-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateRowTotal(newRow);
                calculateAllTotals();
            });
        });
        newRow.querySelector('.remove-row').addEventListener('click', () => {
            newRow.remove();
            calculateAllTotals();
        });
    });

    calculateAllTotals();  // Initial calculation
</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
