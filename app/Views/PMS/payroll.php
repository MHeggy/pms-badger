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

        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>
<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
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
        <div class="row mb-3">
            <label for="project" class="col-sm-2 col-form-label">Project</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="project" name="project">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="description" name="description">
            </div>
        </div>
        
        <!-- Timesheet table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control" name="activity" placeholder="Activity Description">
                    </td>
                    <td><input type="number" class="form-control" id="monday" name="monday" step="0.1"></td>
                    <td><input type="number" class="form-control" id="tuesday" name="tuesday" step="0.1"></td>
                    <td><input type="number" class="form-control" id="wednesday" name="wednesday" step="0.1"></td>
                    <td><input type="number" class="form-control" id="thursday" name="thursday" step="0.1"></td>
                    <td><input type="number" class="form-control" id="friday" name="friday" step="0.1"></td>
                    <td><input type="number" class="form-control" id="saturday" name="saturday" step="0.1"></td>
                    <td><input type="number" class="form-control" id="sunday" name="sunday" step="0.1"></td>
                    <td>
                        <input type="text" class="form-control" id="total-hours" name="total-hours" readonly>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Submit button -->
        <div class="row mt-3">
            <div class="col-sm-10 offset-sm-2">
                <button id="submit-timesheet" type="submit" class="btn btn-primary">Submit Timesheet</button>
            </div>
        </div>
    </form>
</div>

<!-- Simple script to calculate the total hours -->
<script>
    function calculateTotalHours() {
        let totalHours = 0;
        const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        daysOfWeek.forEach(day => {
            const input = document.getElementById(day);
            if (input.value !== '') {
                totalHours += parseFloat(input.value);
            }
        });

        const totalHoursElement = document.getElementById('total-hours');
        totalHoursElement.v
