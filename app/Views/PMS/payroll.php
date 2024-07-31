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
                <!-- Rows will be added here dynamically -->
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
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Add Row Button -->
        <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
                <button type="button" id="add-row" class="btn btn-secondary">Add Row</button>
            </div>
        </div>

        <!-- Submit button -->
        <div class="row mt-3">
            <div class="col-sm-10 offset-sm-2">
                <button id="submit-timesheet" type="submit" class="btn btn-primary">Submit Timesheet</button>
            </div>
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
        document.querySelectorAll('#timesheet-rows tr').forEach(row => {
            calculateRowTotal(row);
        });
    }

    document.querySelectorAll('.day-input').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            calculateRowTotal(row);
        });
    });

    document.querySelectorAll('#timesheet-rows .remove-row').forEach(button => {
        button.addEventListener('click', () => {
            button.closest('tr').remove();
            calculateAllTotals();
        });
    });

    document.getElementById('add-row').addEventListener('click', () => {
        const newRow = document.querySelector('#timesheet-rows tr').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        document.querySelector('#timesheet-rows').appendChild(newRow);
        newRow.querySelectorAll('.day-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateRowTotal(newRow);
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
