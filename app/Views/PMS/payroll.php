<?= $pageTitle = "Timesheets"; ?>
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
                <!-- Existing rows with unique identifiers -->
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[0]"></td>
                    <td><input type="text" class="form-control" name="projectName[0]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[0]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[0]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[0]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[0]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[0]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[0]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[0]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[0]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[0]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[1]"></td>
                    <td><input type="text" class="form-control" name="projectName[1]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[1]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[1]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[1]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[1]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[1]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[1]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[1]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[1]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[1]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[2]"></td>
                    <td><input type="text" class="form-control" name="projectName[2]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[2]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[2]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[2]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[2]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[2]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[2]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[2]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[2]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[2]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[3]"></td>
                    <td><input type="text" class="form-control" name="projectName[3]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[3]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[3]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[3]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[3]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[3]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[3]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[3]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[3]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[3]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[4]"></td>
                    <td><input type="text" class="form-control" name="projectName[4]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[4]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[4]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[4]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[4]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[4]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[4]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[4]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[4]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[4]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[5]"></td>
                    <td><input type="text" class="form-control" name="projectName[5]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[5]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[5]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[5]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[5]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[5]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[5]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[5]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[5]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[5]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[6]"></td>
                    <td><input type="text" class="form-control" name="projectName[6]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[6]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[6]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[6]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[6]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[6]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[6]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[6]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[6]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[6]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="projectNumber[7]"></td>
                    <td><input type="text" class="form-control" name="projectName[7]"></td>
                    <td><input type="text" class="form-control" name="activityDescription[7]"></td>
                    <td><input type="number" class="form-control day-input" name="monday[7]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="tuesday[7]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="wednesday[7]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="thursday[7]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="friday[7]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="saturday[7]" step="0.01"></td>
                    <td><input type="number" class="form-control day-input" name="sunday[7]" step="0.01"></td>
                    <td><input type="text" class="form-control total-hours" name="totalHours[7]" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
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
    let rowCount = 8;  // Adjusting row count for existing rows

    function calculateRowTotal(row) {
        let totalHours = 0;
        const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        daysOfWeek.forEach(day => {
            const input = row.querySelector(`[name^="${day}"]`);
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

    function addEventListenersToRow(row) {
        row.querySelectorAll('.day-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateRowTotal(row);
                calculateAllTotals();
            });
        });
        row.querySelector('.remove-row').addEventListener('click', () => {
            if (!row.querySelector('.remove-row').classList.contains('disabled')) {
                row.remove();
                calculateAllTotals();
            }
        });
    }

    document.querySelectorAll('.day-input').forEach(input => {
        addEventListenersToRow(input.closest('tr'));
    });

    document.querySelectorAll('#timesheet-rows .remove-row').forEach(button => {
        addEventListenersToRow(button.closest('tr'));
    });

    document.getElementById('add-row').addEventListener('click', () => {
        rowCount++;
        const newRow = document.querySelector('#timesheet-rows tr').cloneNode(true);
        
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            // Update names with unique identifiers
            input.name = input.name.replace(/\[\d+\]/, `[${rowCount}]`);
        });

        newRow.querySelector('.remove-row').classList.remove('disabled');
        document.querySelector('#timesheet-rows').appendChild(newRow);
        addEventListenersToRow(newRow);
    });

    calculateAllTotals();  // Initial calculation
</script>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
