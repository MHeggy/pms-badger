<?= esc($pageTitle = "Edit Timesheet") ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px;
        }
        .container {
            max-width: 600px;
            border-top: 80px;
        }
        .remove-row.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        .button-container {
            margin-top: 20px;
        }
    </style>

<header>
    <?php include 'header.php'; ?>
</header>

<div class="container mt-5">
    <form id="timesheet-form" action="/timesheets/update" method="post">
        <input type="hidden" name="id" value="<?= esc($timesheet['timesheetID']) ?>">

        <div class="mb-3">
            <label for="week" class="form-label">Week</label>
            <input type="date" class="form-control" id="week" name="week" value="<?= esc($timesheet['weekOf']) ?>" required>
        </div>

        <!-- Timesheet table -->
        <table class="table">
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
                            <td><input type="text" class="form-control" name="description[]" value="<?= esc($entry['activityDescription']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="monday[]" step="0.01" value="<?= esc($entry['mondayHours']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="tuesday[]" step="0.01" value="<?= esc($entry['tuesdayHours']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="wednesday[]" step="0.01" value="<?= esc($entry['wednesdayHours']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="thursday[]" step="0.01" value="<?= esc($entry['thursdayHours']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="friday[]" step="0.01" value="<?= esc($entry['fridayHours']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="saturday[]" step="0.01" value="<?= esc($entry['saturdayHours']) ?>"></td>
                            <td><input type="number" class="form-control day-input" name="sunday[]" step="0.01" value="<?= esc($entry['sundayHours']) ?>"></td>
                            <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly value="<?= esc($entry['totalHours']) ?>"></td>
                            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Initial Row -->
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
                        <td><input type="text" class="form-control total-hours" name="totalHours[]" readonly></td>
                        <td><button type="button" class="btn btn-danger remove-row disabled">Remove</button></td>
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

        <!-- Add Row Button -->
        <div class="button-container">
            <button type="button" id="add-row" class="btn btn-secondary">Add Row</button>
        </div>

        <!-- Submit Button -->
        <div class="button-container">
            <button type="submit" class="btn btn-primary">Update Timesheet</button>
        </div>
    </form>
</div>

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
        const newRow = document.querySelector('#timesheet-rows tr:last-child').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('.remove-row').classList.remove('disabled');
        document.getElementById('timesheet-rows').appendChild(newRow);
        addEventListenersToRow(newRow);
        calculateAllTotals();
    });

    // Initial calculation
    calculateAllTotals();
</script>

</body>
</html>
